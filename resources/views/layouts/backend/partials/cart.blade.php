{{--code improved Md. Al imrun Khandakar--}}
<style>
    @media (max-width: 600px){
        .navbar-tool {
            margin-left: 0.3rem !important;
        }
        .navbar-tool-icon.czi-cart {
            color: white !important;
        }
    }
</style>
<div class="nav-item dropdown">
    <a class="nav-link pr-0 d-flex" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="media align-items-center">
            <span class="navbar-tool-label cartNumber">
                @php($cart=\App\CPU\CartManager::get_cart())
                {{$cart->count()}}
            </span>
            <input type="hidden" id="cartCount" value="{{ $cart->count() }}">
            <i class="navbar-tool-icon fas fa-shopping-cart text-white"></i>
        </div>
        <div class="navbar-tool-text d-flex flex-column" href="javascript:"><small>My Cart</small>
            {{\App\CPU\CartManager::cart_total_applied_discount(\App\CPU\CartManager::get_cart())}}
        </div>
    </a>
    <!-- Cart dropdown-->
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right"
         style="width: 20rem;">
        <div class="widget widget-cart px-3 pt-2 pb-3">
            @if($cart->count() > 0)
                <div style="height: 15rem;" data-simplebar data-simplebar-auto-hide="false">
                    @php($sub_total=0)
                    @php($total_tax=0)
                    @foreach($cart as  $cartItem)
                        <div class="widget-cart-item pb-2">
                            <button class="close text-danger " type="button" onclick="removeFromCart({{ $cartItem['id'] }})"
                                    aria-label="Remove"><span
                                    aria-hidden="true">&times;</span>
                            </button>
                            <div class="media align-items-center">
                                <a class="d-block ml-2"
                                   href="javascript:">
                                    <img width="64"
                                         onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                                         alt="Product"/>
                                </a>
                                <div class="media-body">
                                    <h6 class="widget-product-title">
                                        <a href="javascript:">{{$cartItem['name']}}</a></h6>
                                    {{-- @foreach(json_decode($cartItem['variations'],true) as $key =>$variation)
                                        <span style="font-size: 14px">{{$key}} : {{$variation}}</span><br>
                                    @endforeach --}}
                                    <div class="widget-product-meta">
                                        <span
                                            class="text-muted ml-2">x {{$cartItem['quantity']}}</span>
                                        <span
                                            class="text-accent ml-2">
                                                {{(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php($sub_total+=($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])
                        @php($total_tax+=$cartItem['tax']*$cartItem['quantity'])
                    @endforeach
                </div>
                <hr>
                <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                    <div
                        class="font-size-sm float-left py-2 ">
                        <span class="">{{('Subtotal')}} :</span>
                        <span
                            class="text-accent font-size-base mr-1">
                             {{($sub_total)}}
                        </span>
                    </div>

                    <a class="btn btn-outline-secondary btn-sm" href="javascript:">
                        {{('Expand cart')}}<i
                            class="czi-arrow-left mr-1 ml-n1"></i>
                    </a>
                </div>
                <a class="btn btn-primary btn-sm btn-block" href="javascript:">
                    <i class="czi-card ml-2 font-size-base align-middle"></i>{{('Checkout')}}
                </a>
            @else
                <div class="widget-cart-item">
                    <h6 class="text-danger text-center"><i
                            class="fa fa-cart-arrow-down"></i> {{('Empty')}} {{('Cart')}}
                    </h6>
                </div>
            @endif
        </div>
    </div>
</div>
