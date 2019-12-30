<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Address;
use App\Order;
use App\OrderProduct;
use App\Payment;
use App\Mail\MobileReceiptMail;
use App\Product;
use Mail;
use stdClass;
use Stripe\Stripe;
use Stripe\Charge;
use Validator;

use function GuzzleHttp\json_decode;

class PaymentController extends Controller
{
    //
    protected $successStatus = 200;
    public $secret_key = "sk_test_1bD9yiNMigTHURQahmoS3S4800IPAc8qQX";

    public function __construct()
    {
        Stripe::setApiKey($this->secret_key);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $updatee['default_address'] = 0;
        $update = Address::where('user_id', $user->id)->update($updatee);
        $validator = Validator::make(
            $request->all(),
            [
                'cart' => 'required',
                'get_total' => 'required',
                'stripe' => 'required',

            ],
            [
                'cart.required' => "Cart Required",
                'get_total.required' => "Total Required Required",
                'stripe.required' => "Stripe Key Required",

            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => implode("\n", $validator->errors()->all())], $this->successStatus);
        }
        $data = json_decode($request->cart);
        $user = $request->user();
        $address = Address::where('user_id', $user->id)->orderBy('id','DESC')->first();
        if (empty($address)) {
            return response()->json(['status' => 'error', 'msg' => 'Address Required'], $this->successStatus);
        }
        try {

            $chargeparms = [
                'amount' => $request->get_total * 100,
                "currency" => "usd",
                "source" => $request->stripe,
                "description" => "Your Wish On Payments"
            ];
            try {
                $charge = Charge::create($chargeparms);
                if ($charge) {
                    //Insert Order Detail after successfully charge the Card
                    $dataaa = Order::latest('id')->first(); // This is fetched from database
                    $last = (!empty($dataaa)) ? $dataaa->id : 1;
                    $last++;
                    $invoice_number = "Invoice#" . $last;
                    $getCart = new stdClass;
                    $getCart->invoice_no = $invoice_number;
                    $getCart->email = $user->email;
                    $getCart->order_date = date('y-m-d');
                    $getCart->address1 = $address->address1;
                    $getCart->city =  $address->postcode . ' ,' . $address->city . ' ,' . $address->state;
                    $getCart->phone_no = $address->phone;
                    $getCart->getTotal = $request->get_total;
                    //$getCart->getTotal = $data;
                    //print_r($data);
                    foreach ($data as $kk => $vv) {
                        // var_dump($vv);
                        $product = Product::where('id', $vv->id)->first();
                        $data[$kk]->name = $product->name;
                        $data[$kk]->price = $product->price;
                    }
                    $getCart->data = $data;
                    $this->OrderMail($getCart);
                    $order['user_id'] = $user->id;
                    $order['receipt_no'] = $invoice_number;
                    $order['billing_address_id'] = $address->id;
                    $order['payment_type'] = 'Card';
                    $order['status'] = 'Pending';
                    $order['note'] = $request->note;
                    $orderadded = Order::create($order);
                    
                    $updatee['default_address'] = 1;
                $update = Address::where('id', $address->id)->update($updatee);
                    if ($orderadded) {
                        //Insert Payment After Order Created Succuessfully                    
                        $paymentAdd['order_id'] = $orderadded->id;
                        $paymentAdd['user_id'] = $user->id;
                        $paymentAdd['total_amount'] = $request->get_total;
                        $paymentAdd['transaction_id'] = $charge->id;
                        $paymentAdd['payment_type'] = 'Card';
                        $paymentAdd['status'] = 'Done';
                        Payment::create($paymentAdd);
                        //Insert Orderproduct After Created order

                        $total = 0;
                        foreach ($data as $kk => $vv) {
                            $product = Product::where('id', $vv->id)->first();
                            $orderProduct['order_id'] = $orderadded->id;
                            $orderProduct['product_id'] = $vv->id;
                            $orderProduct['qty'] = $vv->qty;
                            $orderProduct['price'] = $product->price;
                            $total = $vv->qty * $product->price;
                            $orderProduct['total_amount'] = $total;
                            OrderProduct::create($orderProduct);
                            $productData = Product::where('id', $vv->id)->first();
                            $productUpdate['in_stock']  = $productData['in_stock'] - $vv->qty;
                            $productUpdate['track_stock']  = $productData['track_stock'] + $vv->qty;
                            Product::where('id', $vv->id)->update($productUpdate);
                        }
                        return response()->json(['status' => 'success', 'data' => $data, 'msg' => 'Your Order Has Been Placed Successfully!'], $this->successStatus);
                    } else {
                        return response()->json(['status' => 'error', 'msg' => 'Please try Again!'], $this->successStatus);
                    }
                } else {
                    return response()->json(['status' => 'error', 'msg' => 'Please try Again!'], $this->successStatus);
                    session()->flash('error', 'Payment Not Charged!');
                    return redirect()->back();
                }
            } catch (Exception $e) {
                return response()->json(['status' => 'success', 'msg' => $e->getMessage()], $this->successStatus);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'msg' => 'Invalid Data'], $this->successStatus);
        }
    }

    public function OrderMail($getCart)
    {
        return Mail::to($getCart->email)->send(new MobileReceiptMail($getCart));
    }


    public function GetOrdersById(Request $request)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)->get();
        $products = array();
        foreach ($order as $k => $v) {
            $Product = OrderProduct::where('order_id', $v->id)->with('product')->with('product.productImages')->get();
            $products[] = $Product;
        }
        // return response()->json(['status' => 'success', 'data' => $products, 'msg' => 'Data Found'], $this->successStatus);
        // $order = Order::where('user_id', $user->id)->with('order_product.product')->with('address')->with('order_product')->with('payments')->with('order_product.product')->paginate(10);
        if ($products) {
            return response()->json(['status' => 'success', 'data' => $products, 'msg' => 'Data Found'], $this->successStatus);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->successStatus);
        }
    }

    public function orderDetail(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::where('id', $id)->where('user_id', $user->id)->with(['address', 'order_product', 'order_product.product', 'payments'])->first();
        if ($order) {
            return response()->json(['status' => 'success', 'data' => $order, 'msg' => 'Data Found'], $this->successStatus);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->successStatus);
        }
    }
}
