@extends('admin.layout.app')
@section('content')    
<div class="content-div">
<div class="col-md-12 head-input"> <a data-fancybox="" data-type="iframe" data-src="" href="{{ route('addPopupProduct')}}" class="add-user-btn mt-2">Add</a> </div>
        <div class="head col-md-12 d-inline-block">
          <p class="table-heading mt-4">products</p>
        </div>
        <div class="col-md-12">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <th>Label Id</th>
              <th>Category Name</th>
              <th>Name</th>
              <th>Stock</th>
              <th>Price</th>
            
              <th>Size</th>
              <th>For Index Page</th>
              
              <th>Action</th>
            </tr>
            @if(count($product) > 0)
            @foreach ($product as $v)
            <tr>
              <td>{{ $v->label}}</td>
              <td>{{ $v->categories->name}}</td>
              <td>{{ $v->name }}</td>
              <td>{{ $v->in_stock }}</td>
              <td>${{ number_format($v->price,2) }}</td>
              
              <td>{{ $v->size }}</td>
              <td>
                  <select name="is_featured" onchange="getdata('{{$v->id}}',this.value)" class="black-select">
                    <option value="1" {{ $v->is_featured == '1' ? "selected" : "" }}>Featured</option>
                    <option value="0" {{ $v->is_featured == '0' ? "selected" : "" }}>UnFeatured</option>
                </select>
                </td>
              
              <td>
              <a  href="{{ route('view_product',['id'=>$v->id])}}" class="">
                <button class="view-button">View</button>
              </a>
              <a  href="{{ route('edit_product',['id'=>$v->id])}}" class="">
                <button class="view-button">Edit</button>
              </a>
              <a  href="{{ route('delete_product',['id'=>$v->id])}}" class="">
                  <button class="view-button" style="background: red">Delete</button>
                </a>
             
                
                </td>
            </tr>
            @endforeach

            @endif

          </table>
          <div class="paging">
                {{ $product->links()}}
          </div>
        </div>
</div>
<script>
    function getdata(id,val)
    {
      
        var url = "{{ url('changeproductStatus') }}/" + id + "/" + val;
        $.get(url, function(data, status){
          window.parent.location.reload();
    
        });
  
    }
  </script>
@endsection
