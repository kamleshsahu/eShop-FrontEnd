<section class="section-content footerfix padding-bottom">
    <div class="container">
        <div class="row justify-content-md-center mt-5 mb-5">
            <div class="col-md-4">
                <div class="{{ (isset($data['code']) && $data['code'] != '') ? 'error-hide' : '' }}" id="cardLogin">
                    <div class="card mx-auto">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Sign in</h4>
                            <form action="#" method="POST">
                                <input type="hidden" name="last_url" value="{{ session()->get('last-url') }}">
                                @csrf
                                @if(session()->has('err') && trim(session()->get('err')) != "")
                                    <div class="form-group">
                                        <div class="alert alert-danger">{{ session()->get('err') }}</div>
                                    </div>
                                    @php
                                        session()->put('err', '');
                                    @endphp
                                @endif
                                @if(session()->has('suc') && trim(session()->get('suc')) != "")
                                    <div class="form-group">
                                        <div class="alert alert-success">{{ session()->get('suc') }}</div>
                                    </div>
                                    @php
                                        session()->put('suc', '');
                                    @endphp
                                @endif
                                <div class="form-group">
                                    <input class="form-control" placeholder="Mobile No." name="mobile" type="text" required>
                                </div>
                                <div class="form-group">
                                    <input name="password" class="form-control" placeholder="Password" type="password">
                                </div>
                                <div class="form-group">
                                    <a href="#" class="float-right" id='btnForgot'>Forgot password?</a> 
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block"> Login  </button>
                                </div>
                            </form>
                            <p class="text-center mt-4">Don't have account? <a href="#" id="btnRegister">Sign up</a></p>
                        </div>   
                    </div>
                </div>
                <div class="{{ (isset($data['code']) && $data['code'] != '') ? '' : 'error-hide' }}" id="cardRegister">
                    <div class="card mx-auto">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Register</h4>
                            <form action="#" id="formRegister" method="POST">
                                <input type="hidden" name="country_code" value="+1">
                                <input type="hidden" name='action' value='1'>
                                @csrf
                                <div class="form-group">
                                    <div class="alert alert-danger error-hide" id="registerError"></div>
                                </div>
                                <div class="form-group">
                                    <input type="tel" id="phone" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block"> Next  </button>
                                </div>
                                <div id="recaptcha-container"></div>  
                            </form>
                            <p class="text-center mt-4 alreadyLogin">Already have account? <a href="#" class="btnLogin">Login</a></p>
                            <p class="text-center mt-4 backToLogin error-hide" id="backToLogin">Back to <a href="#" class="btnLogin">Login</a></p>
                        </div>
                    </div>
                </div>
                <div class="card-hide">
                    <div class="card mx-auto" id="cardOtp">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Verify Your Mobile Number</h4>
                            <form action="{{ route('register') }}" id="formOtpVerification" method="POST">
                                @csrf
                                <input type="hidden" name="auth_uid" required>
                                <input type="hidden" name="country_code" required>
                                <input type="hidden" name='friends_code' value='{{ (isset($data['code']) && $data['code'] != '') ? $data['code'] : '' }}'>
                                <div class="form-group" id="otpSuccess">
                                    <div class="alert alert-success">OTP Sent To Mobile Number</div>
                                </div>
                                <div class="form-group">
                                    <div class="alert alert-danger error-hide" id="otpError"></div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mobile" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <input type="number" name="otp" class="form-control">
                                    <small class="form-text text-danger error-hide" id="otp-error">Please Enter Valid 6 Digit OTP.</small>
                                </div>
                                <div class="form-group">
                                    <button id="verifyOtp" class="btn btn-primary btn-block"> Verify OTP </button>
                                </div>
                            </form>
                            <p class="text-center mt-4 alreadyLogin">Already have account? <a href="#" class="btnLogin">Login</a></p>
                            <p class="text-center mt-4 backToLogin error-hide" id="backToLogin">Back to <a href="#" class="btnLogin">Login</a></p>
                        </div>
                    </div>
                </div>
                
                <div class="card-hide">
                    <div class="card mx-auto" id="cardResetPassword">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Reset Password</h4>
                            <form action="{{ route('reset-password') }}" id="formResetPassword" method="POST">
                                @csrf
                                <input type="hidden" name="auth_uid" required>
                                <input type="hidden" name="mobile" required>
                                <div class="form-group">
                                    <div class="alert alert-danger error-hide" id="errorResetPassword"></div>
                                </div>
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <input type="text" id="mobileResetPassword" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block"> Reset  </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
var input = document.querySelector("#phone");
window.intlTelInput(input);

$("#formRegister").submit(function(e){
    e.preventDefault();
    $("#registerError").fadeOut();
    var c = $(".iti__selected-flag").attr('title').split(" ");
    c = c[c.length -1];
    $("input[name=country_code]").val(c);
    $("#formRegister").find("button[type=submit]").attr('disabled', 'disabled');

    $.ajax({
        type : "GET",
        cache : false,
        url : "{{ route('already-registered') }}",
        data : { mobile : $("#phone").val()},    // multiple data sent using ajax
        success: function (response) {
            var obj = JSON.parse(response);
            var action = $("input[type=hidden][name=action]").val();
            var goAhead = false;
            if(action == 0){
                if(obj.error === true){
                    goAhead = true;             
                }else{
                    $("#registerError").html('Mobile number is not registered.').show();
                    $("#formRegister").find("button[type=submit]").removeAttr('disabled');  
                }
            }else{
                if(obj.error === true){
                    $("#registerError").html(obj.message).show();
                    $("#formRegister").find("button[type=submit]").removeAttr('disabled');                
                }else{
                    goAhead = true
                }
            }
            if(goAhead){
                var phoneNumber = c + $("#phone").val();
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                    'size': 'invisible',
                    'callback': function(response) {
                        // reCAPTCHA solved, allow signInWithPhoneNumber.
                        onSignInSubmit();
                    }
                });
                var appVerifier = window.recaptchaVerifier;

                var r = firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier).then(function (confirmationResult) {
                    // confirmationResult can resolve with the whitelisted testVerificationCode above.
                    $("#cardRegister").hide();
                    $("#cardOtp").show();
                    $("#formOtpVerification input[type=text][name=mobile]").val(phoneNumber);
                    $("#formOtpVerification input[type=number][name=otp]").focus();
                    console.log("confirmation");
                    console.log(confirmationResult);
                    
                    $("#verifyOtp").click(function(e){
                        e.preventDefault();
                        var otp = $("#formOtpVerification input[type=number][name=otp]");
                        if(otp.val().length == 6){
                            var code = otp.val();
                            confirmationResult.confirm(code).then(function (result) {
                                var user = result.user;
                                if(action == 0){
                                    $("#cardResetPassword").show();
                                    $("#cardOtp").hide();
                                    $("#cardResetPassword input[name=mobile]").val(phoneNumber);
                                    $("#mobileResetPassword").val(phoneNumber);
                                    $("#cardResetPassword input[type=password][name=password]").focus();
                                }else if(action == 1){
                                    $("#formOtpVerification input[type=hidden][name=auth_uid]").val(user.uid);
                                    $("#formOtpVerification").submit();
                                }
                            }).catch(function (error) {                    
                                console.log(error.message);
                                $("#otpSuccess").hide();
                                $("#otpError").html("Invalid OTP. Please Enter Valid OTP").show();
                            });
                        }else{
                            otp.focus();
                            $("#otp-error").show();
                            otp.parent().vibrate({stopAfterTime:2});
                        }
                    });
                }).catch(function (error) {
                    $("#registerError").html('Unable To Send OTP SMS. Kindly Contact administrator').show();
                    console.log('sms not sent');
                    console.log(error);
                });
            }
        }
    });
});
</script>