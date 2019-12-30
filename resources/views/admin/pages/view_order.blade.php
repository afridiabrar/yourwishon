@extends('admin.layout.app')
@section('content')    
<div class="content-div">
  
    <div class="head col-md-12 d-inline-block">
        <p class="table-heading mt-4">View Order</p>
    </div>
    
    <div class="col-md-12">

        <table class="table">
            <thead>
                <tr>
                    <th class="plantmore-product-thumbnail">Product</th>
                    <th class="cart-product-name">Title</th>
                    <th class="plantmore-product-price">Price</th>
                    <th class="plantmore-product-price">Quantity</th>
                    <th class="plantmore-product-price">Total</th>
                                            

                </tr>
            </thead>
            <tbody>        
                 <?php $qty = 0;$total = 0?>                       
                @foreach ($order->order_product as $v)
                <?php  $qty += $v->qty;
                $total += $v->total_amount;
               ?>  
                <?php $img = ($v->product->featured_image) ? $v->product->featured_image : 'assets/images/other/01.jpg'; ?>  
                <tr>
                <td class="plantmore-product-thumbnail"><a href="#"><img style="height: 100px;width:100px" src="{{asset($img)}}" alt=""></a></td>
                    <td class="plantmore-product-name"><a href="#">{{ $v->product->name }}</a></td>
                    <td class="plantmore-product-price"><span class="amount">{{ number_format($v->product->price,2) }}</span></td>
                    <td class="plantmore-product-price"><span class="amount">{{ $v->qty }}</span></td>
                    <td class="plantmore-product-price"><span class="amount">{{ number_format($v->total_amount,2) }}</span></td>
                </tr>
                @endforeach
            </tbody>
            <tfooter><tr>
                    <td class="plantmore-product-thumbnail">-</td>
                    <td>-</td>
                    <td>-</td>
            <td>{{ $qty}}</td>
            <td>{{number_format($total,2)}}</td>
                    </tr>
            </tfooter>
        </table>
    </div>

</div>
  </div>
@endsection
