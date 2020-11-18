<section class="section-content footerfix padding-bottom">
    <div class="container mt-5 ">
        <div class="row">
            @include("themes.$theme.user.sidebar")
            <div class="col-md-9">
                <div class="card shadow-sm w-100 mb-3">
                    <div class="card-body">
                        <p class="card-text ">
                            <em class="fas fa-wallet align-content-left wallet"></em>
                            <span class="text-wrap ">Refer a friend and earn upto 2% when your friend's first order is successfully delivered.
                                Minimum Order amount shoul be Rs. 100 
                                which allows you to earn upto Rs 5000.
                            </span>
                        </p>
                    </div>
                </div>
                <div class="card shadow-sm border-0 w-100 mb-3">
                    <div class="row text-center mb-2">
                        <div class="col gift">
                            <em class="fa fa-gift"></em>
                        </div>
                     </div>
                    <div class="row text-center mb-2">
                        <div class="col">
                            <span class="text-center">Refer & Earn</span>
                        </div>
                    </div>
                    <div class="row text-center mb-2">
                        <div class="col">
                            <span class="text-danger">Your Referral Code</span>
                        </div>
                    </div>
                    <div class="row text-center mb-2">
                        <div class="col">
                            <input type="text" name="refercode" id='referCode' class="rounded border-info text-center refer-border" value="{{ $data['profile']['referral_code'] }}">
                        </div>
                    </div>
                    <div class="row text-center mt-2 mb-2">
                        <div class="col">
                            <span class="text-primary"><a href="#" onclick="copycode()">Tap to Copy</a></span>
                        </div>
                    </div>
                    <div class="row text-center mt-2 mb-3">
                        <div class="col">
                            <a href="{{ route('refer', $data['profile']['referral_code']) }}" target="_blank" class="btn btn-primary rounded text-capitalize refer-share"><em class="fa fa-share-alt"></em> Refer Now</a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</section>
<script>
    function copycode(){
        /* Get the text field */
        var copyText = document.getElementById("referCode");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

    }
</script>