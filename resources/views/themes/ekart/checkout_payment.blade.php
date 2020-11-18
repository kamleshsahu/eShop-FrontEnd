<section class="section-content padding-bottom mt-5">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="row">
                <div class="col-md-3 text-center">
                    <a href="{{ route('checkout') }}"><span class="icon dark"><em class="fa fa-chevron-circle-right delivery-icon"></em> Delivery</span></a>
                </div>
                <div class="col-md-3 text-center">
                    <a href="{{ route('checkout-address') }}"><span class="icon dark"><em class="fa fa-chevron-circle-right delivery-icon"></em> Address</span></a>
                </div>
                <div class="col-md-3 text-center">
                    <span class="icon dark"><em class="fa fa-chevron-circle-right delivery-icon"></em> Payment</span>
                </div>
            </div>
        </div>
  
        <main>
            <div>
                <div class="row"> 
                    <div class="col-md-12" id="balance">
                        <div class="card shadow p-3 mb-4">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input" id="wallet" />
                                <label class="custom-control-label" for="wallet">Wallet Balance</label>   
                            </div>
                            <small class="text-muted custom-control">Total Balance: {{ get_price($data['user']['balance'] ?? '0', false) }}</small>
                        </div>  
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow mb-4">
                            <h3 class="card-title ml-3 mt-3" id="myDec">Select Delivery Day</h3>
                            <table class="table table-borderless table-shopping-cart" aria-describedby="myDec" aria-hidden="true">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <div class="alert alert-danger" id="dateError">Select Suitable Delivery Date</div>
                                            </div>                                        
                                            <div class="col-md-5">
                                                <div class="form-group calender w-100">
                                                    <div id="calendar">
                                                        <div id="datepicker"></div>
                                                        <em class="calender-icon fa fa-calendar-o"></em> <span id='deliveryDatePrint'></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h3 class="card-title ml-3" id="myDec3">Select Delivery Time</h3>
                            <table class="table table-borderless table-shopping-cart" aria-describedby="myDec3" aria-hidden="true">        
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <div class="alert alert-danger" id="timeError">Select Payment Suitable Delivery Time</div>
                                            </div>
                                            <div class="form-group"  id="time">
                                                <label class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="deliverTime" value="Morning 9 AM to 12 PM" id="morning" checked>
                                                    <span class="custom-control-label"> Morning 9 AM to 12 PM </span>
                                                </label>

                                                <label class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="deliverTime" value="Afternoon 1 PM to 2 PM" id="mid-afternoon">
                                                    <span class="custom-control-label"> Afternoon 1 PM to 2 PM </span>
                                                </label>

                                                <label class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="deliverTime" value="Afternoon 2 PM to 6 PM" id="afternoon">
                                                    <span class="custom-control-label"> Afternoon 2 PM to 6 PM </span>
                                                </label>
                                                
                                                <label class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="deliverTime" value="Evening 7 PM to 9 PM" id="evening">
                                                    <span class="custom-control-label"> Evening 7 PM to 9 PM </span>
                                                </label>    
                                            </div> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow">
                            <h3 class="card-title ml-3 mt-3" id="myDec2">Payment Method</h3>
                            <table class="table table-borderless table-shopping-cart" aria-describedby="myDec2" aria-hidden="true">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <div class="alert alert-danger" id="paymentError">Select Payment Method</div>
                                            </div>
                                            <div class="form-group">
                                                @if(isset(Cache::get('payment_methods')->cod_payment_method) && Cache::get('payment_methods')->cod_payment_method == 1)
                                                    <label class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" value="cod" name='payment_method'>
                                                        <span class="custom-control-label"> Cash On Delivery</span>
                                                    </label>
                                                @endif
                                                @if(isset(Cache::get('payment_methods')->paypal_payment_method) && Cache::get('payment_methods')->paypal_payment_method == 1)
                                                    <label class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" value="paypal" name='payment_method'>
                                                        <span class="custom-control-label"> Paypal</span>
                                                    </label>
                                                @endif
                                                @if(isset(Cache::get('payment_methods')->payumoney_payment_method) && Cache::get('payment_methods')->payumoney_payment_method == 1)
                                                    <label class="custom-control custom-radio" id="PayUMoney">
                                                        <input class="custom-control-input" type="radio" value="payumoney" name='payment_method'>
                                                        <span class="custom-control-label"> PayUMoney</span>
                                                    </label>
                                                    <label class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" value="payumoney-bolt" name='payment_method'>
                                                        <span class="custom-control-label"> PayUMoney</span>
                                                    </label>
                                                @endif
                                                @if(isset(Cache::get('payment_methods')->razorpay_payment_method) && Cache::get('payment_methods')->razorpay_payment_method == 1)
                                                    <label class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" value="razorpay" name='payment_method'>
                                                        <span class="custom-control-label"> Razorpay</span>
                                                    </label>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="text-center mb-3">
                                <a role="button" data-confirm="Confirm Order Amount">
                                    <button id='proceed' class="btn btn-success text-uppercase add-to-cart">
                                        Proceed <em class="fa fa-arrow-right"></em>
                                    </button>
                                </a>  
                            </p>
                        </div>
                    </div>
                </div>
            </div> 

            <!-- The Modal -->
            <div id="orderConfirm" class="modal">
                <div class="modal-dialog ml-5">
                    <div class="modal-content">
                        <div class="modal-header">
                            Confirm Order Amount
                            <div class=" mb-0 mr-4 row">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                        <div class="modal-body"> 
                            <div class="">
                                <tr class="mr-5">
                                    <td>
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
                                        <p class="product-name">Total : <span> {{ $data['total'] }}</span></p>
                                    </td>
                                </tr>  
                                <tr class="text-left">
                                    <td><strong><p class="checkout-total">Final Total : <span>{{ $data['total'] }}</span></p></strong></td> 
                                </tr> 
                            </div>
                            <div class="row add-to-fav1 mr-4">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <form action="{{ route('checkout-proceed') }}" method="POST">
                                    <input type="hidden" name="deliverDay" id="date" value="">
                                    <input type="hidden" name="deliveryTime" value="">
                                    <input type="hidden" name="paymentMethod" value="">
                                    <input type="hidden" name="wallet_used" value="false">
                                    <input type="hidden" name="wallet_balance" value="0">
                                    <button type="submit" name="submit" value="submit" class="btn btn-primary ml-2">Confirm</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>
<script>
$(function(){  
    /* datepicker start*/
    $('#datepicker').datepicker({
        dateFormat:'dd-mm-yy',
        minDate: '0d',
        maxDate: "+28d",
        todayHighlight: true,
        inline: true,  
        altField: "#date",
    }).change(function(e){
        var daySelected = $("[name=deliverDay]").val();
        $("#deliveryDatePrint").html(daySelected);

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); 
        var yyyy = today.getFullYear();
        todayDate = dd + '-' + mm + '-' + yyyy;
        var currentTime = new Date();
        var currentHours = currentTime.getHours();
        
        if(daySelected == todayDate){
            if (currentHours < 9){
                document.getElementById("morning").disabled = true;
                document.getElementById("afternoon").disabled = true;
                document.getElementById("mid-afternoon").disabled = true;
                document.getElementById("evening").disabled = true;

            }
            else if (currentHours >= 9 && currentHours <=12){

                document.getElementById("morning").disabled = false;
                document.getElementById("afternoon").disabled = false;
                document.getElementById("mid-afternoon").disabled = false;
                document.getElementById("evening").disabled = false;  
            }
            if (currentHours >= 12){

                document.getElementById("morning").disabled = true;
                document.getElementById("mid-afternoon").disabled = false;
                document.getElementById("afternoon").disabled = false;
                document.getElementById("evening").disabled = false;
            }
            if (currentHours >= 15){

                document.getElementById("morning").disabled = true;
                document.getElementById("mid-afternoon").disabled = true;
                document.getElementById("afternoon").disabled = false;
                document.getElementById("evening").disabled = false;
            }
            if (currentHours >= 18){
                
                document.getElementById("morning").disabled = true;
                document.getElementById("afternoon").disabled = true;
                document.getElementById("mid-afternoon").disabled = true;
                document.getElementById("evening").disabled = false;
            }
            if (currentHours >= 21){

                document.getElementById("morning").disabled = true;
                document.getElementById("afternoon").disabled = true;
                document.getElementById("mid-afternoon").disabled = true;
                document.getElementById("evening").disabled = true;
            }
            if(document.getElementById("morning").disabled == true && document.getElementById("morning").checked == true){
                document.getElementById("mid-afternoon").checked = true;
            }
            if(document.getElementById("mid-afternoon").disabled == true && document.getElementById("mid-afternoon").checked == true){
                document.getElementById("afternoon").checked = true;
            }
            if(document.getElementById("afternoon").disabled == true && document.getElementById("afternoon").checked == true){
                document.getElementById("evening").checked = true;
            }
            if(document.getElementById("evening").disabled == true && document.getElementById("evening").checked == true){
                document.getElementById("evening").checked = false;
                $("#dateError").html('No Timeslot Available. Please Select Another Date').show();
            }
        }else {
            $("input[type=radio][name=deliverTime]").removeAttr('disabled', 'disabled');

            $("input[type=radio][name=deliverTime]:first").prop('checked', true);
        }
    }).change();

    $("input[type=radio][name=deliverTime]").change(function(e){
        $("input[type=hidden][name=deliveryTime]").val($(this).val());
    }).change();

    $("input[type=radio][name=payment_method]").change(function(e){
        $("input[type=hidden][name=paymentMethod]").val($(this).val());
    }).change();
    
    $("#proceed").click(function(e){
        $("#paymentError").hide();
        $("#timeError").hide();
        $("#dateError").hide();
        var availablePaymentMethods = ["cod", "paypal", "payumoney", "payumoney-bolt", "razorpay"]
        var paymentMethod = $("input[type=radio][name=payment_method]:checked").val();
        
        if($("input[type=hidden][name=deliverDay]").val() != ""){
            if($("input[type=hidden][name=deliveryTime]").val() != ""){
                if(availablePaymentMethods.indexOf(paymentMethod) > -1){
                    $("#orderConfirm").modal('show');
                }else{
                    $("#paymentError").show();
                }
            }else{
                $("#timeError").show();
            }
        }else{
            $("#dateError").html('Select Suitable Delivery Date').show();
        }
    });
    $("#wallet").change(function(e){
        e.preventDefault();
        if ($(this).prop('checked')==true){
            $("input[type=hidden][name=wallet_used]").val('true');
            $("input[type=hidden][name=wallet_balance]").val('{{ $data['user']['balance'] ?? '0' }}');
        }else{
            $("input[type=hidden][name=wallet_used]").val('false');
            $("input[type=hidden][name=wallet_balance]").val('0');
        }
    });
});
</script>