@extends('admin.layout.app')
@section('content')
<div class="content-div">
<div class="col-md-12 head-input"> <a data-fancybox="" data-type="iframe" data-src="" href="{{ route('addPopup')}}" class="add-user-btn mt-2">Add</a> </div>
        <div class="head col-md-12 d-inline-block">
          <p class="table-heading mt-4">categories</p>
        </div>
        <div class="col-md-12">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <th width="30%">Categories</th>
              <th width="50%">Image</th>

              <th width="20%">Action</th>
            
            </tr>
            @if(count($category) > 0)
            @foreach ($category as $item)
            <?php $img = ($item->icon) ? $item->icon : 'public/adminassets/images/product1.jpg' ?>
            <tr>
            <td>{{ $item->name}}</td>
            <td><img class="" style="height: 50px;width;50px" src="{{ asset($img) }}"></td>
            <td>
                <select name="status" onchange="getdata('{{$item->id}}',this.value)" class="black-select">
                  <option value="Featured" {{ $item->status == 'Featured' ? "selected" : "" }}>Featured</option>
                  <option value="UnFeatured" {{ $item->status == 'UnFeatured' ? "selected" : "" }}>UnFeatured</option>
              </select>
              </td>
              </tr>    
            @endforeach
            @endif
          </table>
          <div class="paging">
                  {{ $category->links()}}
            </div>
        </div>
</div>
@endsection
<script>
    function getdata(id,val)
    {
      
        var url = "{{ url('changeCategorystatus') }}/" + id + "/" + val;
        $.get(url, function(data, status){
          window.parent.location.reload();
    
        });
  
    }
  </script>
