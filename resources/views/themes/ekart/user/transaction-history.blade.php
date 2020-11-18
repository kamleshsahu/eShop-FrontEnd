<section class="padding-bottom footerfix section-content">   
    <div class="container mt-5">
        @if(isset($data['list']) && isset($data['list']['data']) && count($data['list']['data']))
            @foreach($data['list']['data'] as $w)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">  
                            <div class="row mt-2 mb-0">
                                <div class="ml-3 form-group col"><span class="font-weight-bold">ID #{{ $w->id }}<span></div>
                            </div>
                            <hr class="m-0">
                            <div class="m-2 ml-3"> 
                                <div class="row  mb-0">
                                    <div class="form-group col"><span class="font-weight-bold"><a>Via {{ strtoupper($w->type) }}</a><span></div>
                                    <div class="mr-5 form-group">
                                        <div class="wallet-header">
                                            <button class="btn btn-sm btn-{{ ($w->status == 'canceled') ? 'danger' : 'success'}}">{{ strtoupper($w->status) }}</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-0">
                                    <div class="form-group col m-0">
                                        <span class="text-muted mt-0">
                                            Date & Time
                                        </span>
                                    </div>
                                    <div class="mr-5 form-group"><span class="font-weight-bold"><a>Amount : {{ get_price($w->amount, false) }}</a></span></div>   
                                </div>
                                
                                <p class="card-title product-name">{{ date('d-M-Y H:i A', strtotime($w->date_created)) }}</p>
                                <span class="text-muted mb-0">Message</span>
                                <p class="text-dark mb-0">
                                    <span class="product-name">{{ $w->message }}</span>
                                </p>   
                            </div>  
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row text-center">
                <div class="col-12">
                    <br><br>
                    <h3>No Transaction History Found</h3>
                </div>
                <div class="col-12">
                    <br><br>
                    <a href="{{ route('shop') }}" class="btn btn-primary"><em class="fa fa-chevron-left mr-1"></em>Continue shopping</a>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col">
                @if(isset($data['last']) && $data['last'] != "")
                    <a href="{{ $data['last'] }}" class="btn btn-primary pull-left text-white"><em class="fa fa-arrow-left"></em>Previous</a>
                @endif
            </div>
            <div class="col">
                @if(isset($data['next']) && $data['next'] != "")
                    <a href="{{ $data['next'] }}" class="btn btn-primary pull-right text-white">Next <em class="fa fa-arrow-right"></i></a>
                @endif
            </div>
        </div>
    </div> 
</section>