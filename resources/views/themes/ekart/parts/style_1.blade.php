{{-- trending products --}}

@if(isset($s->products) && is_array($s->products) && count($s->products))
    <!-- section trending products -->
    <section class="section-content padding-bottom">
        <div class="container">
            <h4 class="title-section text-uppercase font-weight-bold">{{ $s->title }} <small class="text-secondary short-desc">{{ $s->short_description }}</small></h4>
            <hr class="line">
            <div class="card-product-trend card-deal">
                <div class="row no-gutters items-wrap mx-auto">
                    @php   $maxProductShow = get('style_1.max_product_on_homne_page'); @endphp
                    @foreach($s->products as $p)
                        @if((--$maxProductShow) > -1)
                            <div class="col-md col-6">
                                <figure class="card-product-grid card-sm">
                                    <a href="{{ route('product-single', $p->slug) }}" class="img-wrap">
                                        <img src="{{ $p->image }}" alt="{{ $p->name ?? 'Product Image'}}">
                                    </a>
                                    <div class="text-wrap p-3 text-left">
                                        <a href="{{ route('product-single', $p->slug) }}" class="title font-weight-bold product-name mb-2">{{ $p->name }}</a>
                                        <span class="text-muted style-desc"><?= substr($p->description, 0,80)?></span>
                                        <div class="price mt-2 ">
                                            <strong>{!! print_price($p) !!}</strong> &nbsp; <s class="text-muted">{!! print_mrp($p) !!}</s>
                                            <small class="text-success"> {{ get_savings_varients($p->variants[0]) }} </small>
                                        </div>
                                    </div>
                                </figure>
                            </div>
                        @else
                            @break
                        @endif
                    @endforeach

                    <div class="col-heading content-body col-md-3 col-6">       
                        <header class="section-heading">
                            <h3 class="section-title ml-4">{{ $s->title }}</h3>
                            <p class="ml-4">{{ $s->short_description }}</p>
                        </header><!-- sect-heading -->

                        <div class="col text-left ml-2">
                            <a href="{{ route('shop', ['section' => $s->slug]) }}" class="view-all"><button type="button" class="btn btn-primary">View All</button></a>
                        </div>
                    </div> <!-- col.// -->
                </div>
            </div>
        </div>
    </section>
    <!--end tranding products-->
@endif