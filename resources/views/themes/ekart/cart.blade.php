@include("themes.$theme.common.msg")
<section class="section-content footerfix padding-bottom">
    <div class="container mt-5">
        <h2 class="mb-5 text-center">Shopping Cart</h2>
        @if(Cache::has('min_order_amount') && intval($data['subtotal']) <= intval(Cache::get('min_order_amount')))
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="alert alert-warning">You must have to purchase {{ get_price(Cache::get('min_order_amount')) }} to place order</div>
                </div>
            </div>
        @elseif(intval(Cache::get('min_amount', 0)) && intval($data['shipping']))
            @if(intval($data['subtotal']) && intval($data['subtotal']) < Cache::get('min_amount'))
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div class="alert alert-info">You can get Free Delivery By Shopping More Than {{ get_price(Cache::get('min_amount')) }}</div>
                    </div>
                </div>
            @endif
        @endif
        <div class="row justify-content-center">
            <main class="col-md-9">
                <div class="card">
                    <div class="table-responsive">
                        <table id="myTable" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col" width="120">Price</th>
                                    <th scope="col" class="text-right" width="200"> </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data['cart']) && is_array($data['cart']) && count($data['cart']))
                                    @foreach($data['cart'] as $p)
                                        @if(isset($p->item))
                                            <tr>
                                                <td>
                                                    <a href="{{ route('product-single', $p->item[0]->slug) }}">
                                                        <figure class="itemside">
                                                            <div class="aside">
                                                                <img src="{{ $p->item[0]->image }}" class="img-sm" alt="{{ $p->item[0]->name ?? 'Product Image' }}">
                                                            </div>
                                                            <figcaption class="info">
                                                                <a href="{{ route('product-single', $p->item[0]->slug) }}" class="title text-dark">{{ $p->item[0]->name ?? '-' }}</a>
                                                                <p class="small text-muted">{{ get_varient_name($p->item[0]) ?? '-' }}</p>
                                                            </figcaption>
                                                        </figure>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <div class="price-wrap cartShow">{{ $p->qty }}</div>
                                                    <form action="{{ route('cart-update', $p->product_id) }}" method="POST" class="cartEdit">
                                                        @csrf
                                                        <input type="hidden" name="child_id" value="{{ $p->product_variant_id }}">
                                                        <input type="hidden" name="product_id" value="{{ $p->product_id }}">
                                                        <div class="button-container col">
                                                            <button class="cart-qty-minus button-minus" type="button" id="button-minus" value="-">-</button>
                                                            <input class="form-control qtyPicker" type="number" name="qty" data-min="1" min="1" max="{{ intval(getMaxQty($p->item[0])) }}" data-max="{{ intval(getMaxQty($p->item[0])) }}" value="{{ $p->qty }}">
                                                            <button class="cart-qty-plus button-plus" type="button" id="button-plus" value="+">+</button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td> 
                                                    <div class="price-wrap"> 
                                                        <var class="price">
                                                            @if(intval($p->item[0]->discounted_price))
                                                                {{ get_price($p->item[0]->discounted_price * ($p->qty ?? 1) ) }}
                                                            @else
                                                                {{ get_price($p->item[0]->price * ($p->qty ?? 1) ) }}
                                                            @endif
                                                        </var> 
                                                        @if(intval($p->qty) > 1)
                                                            @if(intval($p->item[0]->discounted_price))
                                                                <br><small class="text-muted"> {{ get_price($p->item[0]->discounted_price) }}{{ ($p->qty > 0) ? ' each' : '' }}</small> 
                                                            @else
                                                                <br><small class="text-muted"> {{ get_price($p->item[0]->price) }}{{ ($p->qty > 0) ? ' each' : '' }}</small> 
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <button class="btn btn-light btn-round btnEdit cartShow"> <em class="fa fa-pencil"></em></button>
                                                    <button class="btn btn-light btn-round cartSave cartEdit"> <em class="fa fa-check"></em></button>
                                                    <button class="btn btn-light btn-round btnEdit cartEdit"> <em class="fa fa-times"></em></button>
                                                    <a href="{{ route('cart-remove', $p->product_variant_id ) }}" class="btn btn-light btn-round"> <em class="fa fa-trash"></em></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <img src="{{ asset('images/empty-cart.png') }}" alt="No Items In Cart">
                                        <br><br>
                                        <a href="{{ route('shop') }}" class="btn btn-primary"><em class="fa fa-chevron-left  mr-1"></em>Continue shopping</a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            @if(isset($data['cart']) && is_array($data['cart']) && count($data['cart']))
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td class="text-center" colspan="2">
                                            <p class="product-name">Subtotal : <span>{{ get_price($data['subtotal']) ?? '-' }}</span></p>
                                            @if(isset($data['tax_amount']) && floatval($data['tax_amount']))
                                                <p class="product-name">Tax {{ $data['tax'] }}% : <span>+ {{ get_price($data['tax_amount']) }}</span></p>
                                            @endif                                       
                                            @if(isset($data['shipping']) && floatval($data['shipping']))
                                                <p class="product-name">Delivery Charge : <span>+ {{ get_price($data['shipping']) }}</span></p>
                                            @endif
                                            @if(isset($data['coupon']['discount']) && floatval($data['coupon']['discount']))
                                                <p class="product-name">Discount : <span>- {{ get_price($data['coupon']['discount']) }}</span></p>
                                            @endif
                                            <p class="product-name">Total : <span> {{ get_price($data['total']) ?? '-' }}</span></p>
                                        </td>
                                    </tr>  
                                    <tr>
                                        <td><strong><span><a href="{{ route('shop') }}" class="btn btn-primary"><em class="fa fa-chevron-left mr-1"></em>Continue shopping</a></span></strong></td>
                                        @if(isset($data['cart']) && is_array($data['cart']) && count($data['cart']))
                                            <td></td>
                                            <td colspan="2" class="text-right">
                                                <a href="{{ route('cart-remove', 'all' ) }}" class="btn btn-primary">Delete All <em class="fa fa-trash"></em></a>
                                                @if(Cache::has('min_order_amount') && intval($data['subtotal']) >= intval(Cache::get('min_order_amount')))
                                                    <a href="{{ route('checkout') }}" class="btn btn-primary">Checkout <em class="fa fa-arrow-right"></em></a>
                                                @else
                                                    <button class="btn btn-primary" disabled>Checkout <em class="fa fa-arrow-right"></em></button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>