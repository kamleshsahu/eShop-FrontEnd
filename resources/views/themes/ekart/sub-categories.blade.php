<section class="footerfix section-content padding-bottom mt-5">
    <div class="container">
        <nav class="row row-eq-height">
            @if(isset($data['sub-categories']))
                @foreach ($data['sub-categories'] as $c)
                    <div class="col-md-3 mt-2">
                        <a href="{{ route('shop', ['category' => $data['category']->slug, 'sub-category' => $c->slug]) }}">
                            <div class="card card-category eq-height-element">
                                <div class="img-wrap">
                                    <img src="{{ $c->image }}" alt="{{ $c->name ?? '' }}">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">{{ $c->name }}</h4>
                                    <p>{{ $c->subtitle }}</p>
                                </div>
                            </div>
                        </a>
                    </div>               
                @endforeach
            @endif
        </nav>
    </div>
</section>