<div class="section-content footerfix">
    <div class="container mt-5 padding-bottom"> 
        <div class="row">
            <div class="col">
                <div id="accordion">
                    @if(count($data['faq']))
                        @foreach($data['faq'] as $faq)
                            <div class="card">
                                <div class="card-header" id="heading{{ $faq->id }}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $faq->id }}" aria-expanded="true" aria-controls="collapse{{ $faq->id }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h5>
                                </div>
                            
                                <div id="collapse{{ $faq->id }}" class="collapse show" aria-labelledby="heading{{ $faq->id }}" data-parent="#accordion">
                                    <div class="card-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row text-center">
                            <div class="col-12">
                                <br><br>
                                <h3>No FAQs Found.</h3>
                            </div>
                            <div class="col-12">
                                <br><br>
                                <a href="{{ route('shop') }}" class="btn btn-primary"><em class="fa fa-chevron-left mr-1"></em> Continue shopping</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col">

                @if(isset($data['page']) && $data['page'] > 0)

                    <a href="{{ route('faq'). (intval($data['page']-1) ? '?page='.($data['page']-1) : '') }}" class="btn btn-primary"><em class="fa fa-chevron-left"></em> Previous</a>

                @endif

            </div>

            <div class="col text-right">

                @if(isset($data['page']) && $data['page'] != intval($data['total']/$data['limit']))

                    <a href="{{ route('faq') }}?page={{ $data['page']+1 }}" class="btn btn-primary"> Next <em class="fa fa-chevron-right"></em></a>

                @endif

            </div>

        </div>
    </div>
</div>