@include("themes.$theme.common.msg")
<script src="{{ theme('js/checkout.js') }}"></script>
<section class="section-content padding-bottom mt-5">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="row">
                <div class="col-md-3 text-center">
                    <span class="icon dark"><em class="fa fa-chevron-circle-right delivery-icon"></em> Delivery</span>   
                </div>
                <div class="col-md-3 text-center payment-icon">
                    <span class="icon dark"><em class="fa fa-chevron-circle-right"></em> Address</span>
                </div>
                <div class="col-md-3 text-center payment-icon">
                    <span class="icon dark"><em class="fa fa-chevron-circle-right"></em> Payment</span>
                </div>
            </div>
        </div>
        <main>
            <div class="row" id="delivery">
                <div class="col-12">               
                    <div class="card shadow mb-4">
                        <div class="card-body"> 
                            @if(intval($data['coupon'] ?? 0))
                                <div class="form-group" id='couponAppliedDiv'>
                                    <label>Coupon Code</label>
                                    <div class="alert alert-success">{{ $data['coupon']['promo_code_message'] }}</div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $data['coupon']['promo_code'] }}" disabled="disabled" placeholder="Coupon code">
                                        <span class="input-group-append"> 
                                            <a href="{{ route('coupon-remove') }}" class="btn btn-danger" id='removeCoupon'>x</a>
                                        </span>
                                    </div>
                                </div>
                            @endif                           
                            <form action="{{ route('coupon-apply') }}" method="POST" class='ajax-form {{ intval($data['coupon'] ?? 0) ? 'address-hide' : '' }}' id='couponForm'>
                                <input type="hidden" name="total" value="{{ $data['total'] }}">
                                <div class="form-group">
                                    <label>Have a Promo Code?</label>
                                    <div class='formResponse'></div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="coupon" value="{{ $data['coupon']['promo_code'] ?? '' }}" placeholder="Coupon code">
                                        <span class="input-group-append"> 
                                            <button class="btn btn-primary">Apply</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>  
                </div>  
            </div>   

            <div class="row" id="summary">
                <div class="col-12">
                    <div class="card shadow mb-4">  
                        <div class="table-responsive">
                            <p class="product-name pb-0 font-weight-bold head" id="myDec">Order Summary</p>
                            <table id="myTable" class="table" aria-describedby="myDec">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($data['cart']) && is_array($data['cart']) && count($data['cart']))

                                        @foreach($data['cart'] as $p)

                                            @if(isset($p->item))

                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            <div class="product-img">
                                                                <figcaption class="info-wrap">
                                                                    <a href="#" class="product-name">{{ strtoupper($p->item[0]->name) ?? '-' }}</a>
                                                                    <p class="small text-muted">{{ get_varient_name($p->item[0]) }}</p>
                                                                </figcaption>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td>{{ $p->qty }}</td>
                                                    <td>
                                                        @if(intval($p->item[0]->discounted_price))
                                                            {{ $p->item[0]->discounted_price ?? '' }}
                                                        @else
                                                            {{ $p->item[0]->price ?? '' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(intval($p->item[0]->discounted_price))
                                                            {{ $p->item[0]->discounted_price * ($p->qty ?? 1) }}
                                                        @else
                                                            {{ $p->item[0]->price * ($p->qty ?? 1) }}
                                                        @endif
                                                    </td>
                                                </tr> 

                                            @endif

                                        @endforeach

                                    @endif

                                    <tfoot class="text-right"> 
                                        <tr class="mr-5">
                                            <td colspan="4">
                                                <p class="product-name">Subtotal : <span>{{ $data['subtotal'] ?? '-' }}</span></p>
                                                @if(isset($data['tax_amount']) && floatval($data['tax_amount']))
                                                    <p class="product-name">Tax {{ $data['tax'] }}% : <span>+ {{ get_price($data['tax_amount']) }}</span></p>
                                                @endif
                                                @if(isset($data['shipping']) && floatval($data['shipping']))
                                                    <p class="product-name">Delivery Charge : <span>+ {{ get_price($data['shipping']) }}</span></p>
                                                @endif
                                                @if(isset($data['coupon']['discount']) && floatval($data['coupon']['discount']))
                                                    <p class="product-name">Discount : <span>- {{ get_price($data['coupon']['discount']) }}</span></p>
                                                @endif
                                            </td>
                                        </tr>  
                                        <tr class="text-left">
                                            <td><strong><p class="checkout-total">Total : <span>{{ $data['total'] }}</span></p></strong></td>

                                            <td colspan="2"></td>
                                            <td class="text-right">
                                                <strong>
                                                    <span>
                                                        <a href='{{ route('checkout-address') }}' class="btn btn-primary text-uppercase add-to-cart">Confirm <em class="fa fa-check"></em></a>
                                                    </span>
                                                </strong>
                                            </td>    
                                        </tr>                                                      
                                    </tfoot> 
                                </tbody>    
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>             
    </div>
</section>
