
@extends('web.layout.app')
@section('content')
    
<div class="main-wrapper">

    <!-- breadcrumb-area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- breadcrumb-list start -->
                    <ul class="breadcrumb-list">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Category</li>
                    </ul>
                    <!-- breadcrumb-list end -->
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area end -->
    @include('web.layout.error')
    <!-- main-content-wrap start -->
    <div class="main-content-wrap shop-page section-pb mt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 order-lg-1 order-2">
                    <!-- shop-sidebar-wrap start -->
                    <div class="shop-sidebar-wrap">

                        <!-- shop-sidebar start -->
                        <div class="shop-sidebar mb-30">
                            <h4 class="title">All Categories</h4>
                            <ul>
                                @if(count($category) > 0)
                                     @foreach ($category as $v)
                                     <li><a href="{{ route('category') }}/{{ $v->slug}}">{{ $v->name}} </a></li>                                         
                                     @endforeach   
                                @endif
                            </ul>
                        </div>
                        <!-- shop-sidebar end -->
                        <!-- shop-sidebar start -->
                        <div class="sidbar-product mb-30">
                        <a href="#"><img src="{{ asset('public/assets/images/banner/banner-ad-inner.jpg')}}" alt="banner Images"></a>
                        </div>
                        <!-- shop-sidebar end -->
                    </div>
                    <!-- shop-sidebar-wrap end -->
                </div>
                <div class="col-lg-9 order-lg-2 order-1">
                    <!-- shop-product-wrapper start -->
                    <div class="shop-product-wrapper">
                        <div class="row">
                            <div class="col-md-12">
                            {{-- <div class=" mb-30"><a href="#"><img class="img-fluid" src="{{ asset('public/assets/images/banner/computer-banner.jpg')}}" alt="computer banner"></a></div>
                                <div class=" mb-30">
                                <h4>computer accessories</h4>
                                </div> --}}
                                <!-- shop-top-bar start -->
                                <div class="shop-top-bar">
                                    <!-- product-view-mode start -->

                                    <div class="product-mode">
                                        <!--shop-item-filter-list-->
                                        <ul class="nav shop-item-filter-list" role="tablist">
                                            <li class="active"><a class="active" data-toggle="tab" href="#grid"><i class="ion-ios-keypad-outline"></i></a></li>
                                            <li><a data-toggle="tab" href="#list"><i class="ion-ios-list-outline"></i></a></li>
                                        </ul>
                                        <!-- shop-item-filter-list end -->
                                    </div>
                                    <!-- product-view-mode end -->
                                    <!-- product-short start -->
                                    {{-- <div class="product-short">
                                        <select class="nice-select" name="sortby">
                                            <option value="trending">Show</option>
                                            <option value="sales">(1 - 10)</option>
                                            <option value="sales">(11 - 20)</option>
                                            <option value="rating">(21 - 30)</option>
                                            <option value="date">(31 - 40)</option>
                                        </select>
                                    </div> --}}
                                    <!-- product-short end -->
                                </div>
                                <!-- shop-top-bar end -->
                            </div>
                        </div>

                        <!-- shop-products-wrap start -->
                        <div class="shop-products-wrap">
                            <div class="tab-content">
                                <div class="tab-pane active" id="grid">
                                    <div class="shop-product-wrap">
                                        <div class="row">
                                            @if(count($product) > 0 )
                                            @foreach ($product as $item)
                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                    <!-- single-product-wrap start -->
                                                    <div class="single-product-wrap">
                                                        <div class="product-image">
                                                        <a href="{{ route('product-detail',['id'=>$item->id])}}"><img style="height: 200px;width: 200px;object-fit: contain" src="{{ asset($item->featured_image)}}" alt="Produce Images"></a>
                                                            
                                                            <div class="product-action">
                                                                <a href="{{ route('signleCart',['id'=>$item->id])}}" class="add-to-cart"><i class="ion-ios-cart-outline"></i></a>
                                                                <a href="{{ route('product-detail',['id'=>$item->id])}}" class="quick-view" ><i class="ionicons ion-ios-eye-outline"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="product-content">
                                                            <h3><a href="{{ route('product-detail',['id'=>$item->id])}}">{{ $item->name}}</a></h3>
                                                            <div class="price-box">
                                                                
                                                                <span class="new-price">${{ number_format($item->price,2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- single-product-wrap end -->
                                                </div>
                                            @endforeach
                                            @else
                                                    <h4 style="text-align: center;color: #418b47;position: relative;left: 30%;top:20%">No Product Available</h4>
                                            @endif
                                           
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="list">
                                    <div class="shop-product-list-wrap">
                                            @foreach ($product as $item)
                                        <div class="row product-layout-list">
                                            <div class="col-lg-4 col-md-5">
                                                <!-- single-product-wrap start -->
                                                <div class="single-product-wrap">
                                                    <div class="product-image">
                                                    <a href="product-details.html">
                                                        <img src="{{ asset($item->featured_image)}}" alt="Produce Images"></a>
                                                        <div class="product-action">
                                                            <a href="{{ route('signleCart',['id'=>$item->id])}}" class="add-to-cart"><i class="ion-ios-cart-outline"></i>
                                                            </a>
                                                            <a href="{{ route('product-detail',['id'=>$item->id])}}" class="quick-view">
                                                                <i class="ionicons ion-ios-eye-outline"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- single-product-wrap end -->
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="product-content text-left">
                                                    <h3><a href="product-details.html">{{ $item->name }}</a></h3>
                                                    <div class="price-box">
                                                        <span class="new-price">${{ number_format($item->price,2) }}</span>
                                                    </div>
                                                    <p>{{ $item->description}}.</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach



                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- shop-products-wrap end -->

                        <!-- paginatoin-area start -->
                        <div class="paginatoin-area">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <ul class="pagination-box">
                                        {{-- <li><a class="Previous" href="#"><i class="ion-chevron-left"></i></a>
                                        </li>
                                        <li class="active"><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">4</a></li>
                                        <li>
                                            <a class="Next" href="#"><i class="ion-chevron-right"></i> </a>
                                        </li> --}}
                                        {{ $product->links()}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- paginatoin-area end -->
                    </div>
                    <!-- shop-product-wrapper end -->
                </div>
            </div>
        </div>
    </div>
    <!-- main-content-wrap end -->


</div>
@endsection
