@extends('admin.layout.app')
@section('content')
@include('admin.layout.error')
<div class="content-div">
    <div class="head col-md-12 d-inline-block">
      <p class="table-heading mt-4">orders</p>
    </div>
    <div class="col-md-12">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <th>OrderID</th>
          <th>Invoice #id</th>
          <th>User Name</th>
          <th>Address</th>
          <th>Product Name</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
        @foreach ($order as $vvvv)
        <tr>
          <td>{{ $vvvv->id}}</td>
        <td>{{ $vvvv->receipt_no }}</td>
          <td>{{ $vvvv->user->f_name }} {{ $vvvv->user->l_name }}</td>
          <td>
              <p>{{ $vvvv->address->first_name }} {{ $vvvv->address->last_name }}</p>
              <p>{{ $vvvv->address->address1 }} ,{{ $vvvv->address->postcode }},{{ $vvvv->address->city }}</p>
           
          </td>
          <td>
              @foreach ($vvvv->order_product as $vv)
          </br><p><?= $vv->product->name ?>  </p>
            @endforeach
          </td>              
          <td>
          @foreach ($vvvv->order_product as $val)

          </br>${{ number_format($val->product->price,2)}}
          @endforeach
        </td>

        <td>                    
            <select name="status" onchange="getdata('{{$vvvv->id}}',this.value)" class="black-select">
                <option value="Pending" {{ $vvvv->status == 'Pending' ? "selected" : "" }}>Pending</option>
                <option value="Processing" {{ $vvvv->status == 'Processing' ? "selected" : "" }}>Processing</option>
                <option value="Completed" {{ $vvvv->status == 'Completed' ? "selected" : "" }}>Completed</option>
            </select>
            <a   href="{{ route('s_order',['id'=>$vvvv->id]) }}" class=""><button class="view-button">View</button></a>
            </td>
        </tr>
        @endforeach

      </table>
      <div class="paging"> {{ $order->links()}} </div>
    </div>
  </div>
  <script>
      function getdata(id,val)
      {
        
          var url = "{{ url('changeOrderstatus') }}/" + id + "/" + val;
          $.get(url, function(data, status){
            window.parent.location.reload();
      
          });
    
      }
    </script>
@endsection
