@extends('admin.layout.app')
@section('content')    
<div class="content-div">
    @include('web.layout.error')
  
    <div class="head col-md-12 d-inline-block">
      <p class="table-heading mt-2">Edit products</p>
    </div>
    <form action="{{ route('UpdateProduct',['id'=>$product->id]) }}" method="POST" enctype="multipart/form-data">
      @csrf
    <div class="col-md-12">
      <div class="text-left">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
              <td>Label</td>
            <td><input type="text" placeholder="Computer Screen" value="{{$product->label}}" name="label" /></td>
            </tr>
        <tr>
          <td>Name</td>
        <td><input type="text" placeholder="Computer Screen" value="{{$product->name}}" name="name" /></td>
        </tr>
        <tr>
          <td>Update Product Image</td>
          <td><input type="file" name="image" accept="image/*"></td>
        </tr>
        <tr>
          <td></td>
          <td>
          <div class="col-md-12 text-center store-img mb-3 mt-3">
              <?php $img = ($product->featured_image) ? $product->featured_image : 'public/adminassets/images/product1.jpg' ?>
        <ul>
        <li><a href="#"><img class="" style="height: 100px;width;100px" src="{{ asset($img) }}"></a></li>
        </ul>
      </div>
      </td>
        </tr>
        <tr>
          <td>Add Product Gallery</td>
          <td><a data-fancybox="" data-type="iframe" data-src="" href="{{ route('addImageGaleryPopup',['id'=>$product->id])}}" class="view-button">Add Galery Photo</a></td>
        </tr>
        <tr>
          <td></td>
          <td>
          <div class="col-md-12 text-center store-img mb-3 mt-3">
        <ul>
          @foreach ($product->productImages as $item)
          <?php $img = ($item->prouct_images) ? $item->prouct_images : 'public/adminassets/images/product1.jpg' ?>
          @if($item->type == 'image')
        <li><a href="#"><img class="" style="height: 200px;width: 200px;object-fit: contain" src="{{ asset($img) }}"><a href="{{ route('deleteImages',['id'=>$item->id])}}">X</a></li>
          @elseif($item->type == 'video')
          <li><video style="height: 200px;width: 200px;object-fit: contain" src="{{ asset($img) }}" controls>
            </video><span>X</span>
          </li>
          @endif
          @endforeach
        </ul>
      </div>
      </td>
        </tr>
        <tr>
            <td> Add Quantity</td>
            <td><input name="qty"  type="number" /></td>
          </tr>
        
          <tr>
              <td>Buy Price</td>
              <td><input  name="cost_price" value="{{number_format($product->cost_price,2)}}" type="number" placeholder="24" /></td>
            </tr>
        <tr>
          <td>Sell Price</td>
          <td><input type="number" name="price" value="{{ number_format($product->price,2)}}" /></td>
        </tr>
        
        <tr>
          <td>Size</td>
          <td><input type="text" name="size" value="{{$product->size}}"  placeholder="Black" /></td>
        </tr>
        
      
        <tr>
          <td>Discription</td>
        <td><textarea rows="3" cols="50">{{ $product->description}}</textarea></td>
        </tr>
      </table>
      <div class="col-md-12 text-center mt-5"> 
        <button class="view-button" type="submit">Update Product</button>
        
      </div>
    </div>
    </div>
    </form>
  </div>
@endsection
