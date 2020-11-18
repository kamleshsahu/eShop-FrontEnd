$( document ).ready(function() {
    $(".addToCart").submit(function(e){
        //e.preventDefault();
        if($(this).find('select[name=child_id] option:selected').val() == 0){
            $(this).find('select[name=child_id]').focus().vibrate({stopAfterTime:2});
            return false;
        }else{
            return true;
        }
	});
	$(".button-plus").click(function(e){
		var qP=$(this).parent().parent().find('.qtyPicker');
		var nP = parseInt(qP.val())+1;
		if(qP.data('max') > nP){qP.val(nP);$(this).parent().parent().find('.button-minus').removeAttr('disabled')}else{$(this).attr('disabled','disabled')}
	});
	$(".button-minus").click(function(e){
		var qP=$(this).parent().parent().find('.qtyPicker');
		var nP = parseInt(qP.val())-1;
		if(qP.data('min') <= nP){qP.val(nP);$(this).parent().parent().find('.button-plus').removeAttr('disabled')}else{$(this).attr('disabled','disabled')}
	});
	if($('[data-toggle="tooltip"]').length > 0) {
		$('[data-toggle="tooltip"]').tooltip()
	}
	$(".addToCart select[name=child_id]").change(function(e){
		var val = $(this).find('option:selected').val();
		if(val > 0){
			$(".actualPrice").html($(this).find('option:selected').data('price')).show();
			$(".rangePrice").hide()
		}else{
			$(".actualPrice").hide();
			$(".rangePrice").show()
		}
	});

	$("select[name=child_id]").change(function(e){
		var selected = $(this).find('option:selected');
		$("#productPrice" + $(this).data('id')).html(selected.data('price'));
		if(selected.data('mrp') == ""){
			$("#productMrp" + $(this).data('id')).hide();
		}else{
			$("#productMrp" + $(this).data('id')).html(selected.data('mrp')).show();
		}
		$("#productTitle" + $(this).data('id')).html(selected.text());
		if(selected.data('discount') != ""){
			$("#productDiscount" + $(this).data('id')).html(selected.data('discount')).show();
		}else{
			$("#productDiscount" + $(this).data('id')).hide();
		}
	});

	$(".btnEdit").click(function(e){
		$(this).closest('tr').find('.cartShow').toggle();
		$(this).closest('tr').find('.cartEdit').toggle();
	});
	$(".cartSave").click(function(e){
		var tr = $(this).closest('tr');
		if(tr.find('form.cartEdit').find('input[name=qty]').val() == tr.find('.price-wrap.cartShow').html()){
			tr.find('.cartShow').toggle();
			tr.find('.cartEdit').toggle();
		}else{
			tr.find('form.cartEdit').submit();
		}
	});
	$("#btnRegister").click(function(e){
		$("#cardLogin").hide();
		$("#registerError").hide();
		$("#phone").val('');
		$("#cardRegister input[type=hidden][name=action]").val('1');
		$("#cardRegister").show();
		$("#cardRegister .card-title").html('Register');
		$(".alreadyLogin").show();
		$(".backToLogin").hide();
	});
	$(".btnLogin").click(function(e){
		$("#cardLogin").show();
		$("#cardRegister").hide();
	});
	$("#btnForgot").click(function(e){
		$("#cardLogin").hide();
		$("#registerError").hide();
		$("#phone").val('');
		$("#cardRegister input[type=hidden][name=action]").val('0');
		$("#cardRegister .card-title").html('Forgot Password');
		$("#cardRegister").show();
		$(".alreadyLogin").hide();
		$(".backToLogin").show();
	});
	$("#searchForm").submit(function(e){
		e.preventDefault();
		var s = $(this).find('input[name=s][type=text]').val().trim();
		if(s != ""){
			window.location.href = $(this).attr('action') + "/" + s;
		}
	});
	$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
        $(this).closest(".select2-container").siblings('select:enabled').select2('open');
	});
	$("#formResetPassword").submit(function(e){
		e.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(this).serialize(),
			success: function(response){
				var data = JSON.parse(response);
				if(data.status === false){
					$("#errorResetPassword").html(data.message).show();
				}else{
					window.location.href = '/login';
				}
			}
		});
	});
	$(".ajax-form").submit(function(e){

		e.preventDefault();

		var formResponse = $(this).find('.formResponse');

		formResponse.html('').hide();

		var submit = $(this).find("button[type=submit]");

		submit.attr('disabled', 'disabled');

		$.ajax({

			url: $(this).attr('action'),

			type: $(this).attr('method'),

			data: $(this).serialize(),

			success: function(response){

				console.log(response);

				var data = response;

				if(IsJsonString(response) == true){

					data = JSON.parse(response);

				}
	
				var msg = "Something Went Wrong";

				if(data.error === true){

					if(data.message != ""){

						msg = data.message;

					}

					formResponse.html("<div class='alert alert-danger'>" + msg + "</div>").show();

					submit.removeAttr('disabled');

				}else{

					msg = "Success";

					if(data.message != ""){

						msg = data.message;

					}

					formResponse.html("<div class='alert alert-success'>" + msg + "</div>").show();

				}

				if (typeof data.url !== 'undefined' && data.url != "") {

					window.location.href = data.url;

				}else{

					submit.removeAttr('disabled');

				}

			}

		});

	});

	$("#removeCoupon").click(function(e){

		// $("#couponAppliedDiv").hide();

		// $("#couponForm").show();

	});

	// $("input[type=radio][name=deliverDay]").change(function(e){

	// 	var daySelected = $("input[type=radio][name=deliverDay]:checked").val();

	// 	if(daySelected == 0){

	// 		$("input[type=radio][name=deliverTime]").attr('disabled', 'disabled').prop('checked', false);

	// 	}else{

	// 		$("input[type=radio][name=deliverTime]").removeAttr('disabled', 'disabled');

	// 		$("input[type=radio][name=deliverTime]:first").prop('checked', true);

	// 	}

	// }).change();

	$("#checkoutProceedBtn").click(function(e){

		e.preventDefault();

		$("#paymentMethodError").html('').hide();

		var selectedPaymentMethod = $("input[type=radio][name=paymentMethod]:checked");

		if (typeof selectedPaymentMethod.val() === 'undefined') {

			$("#paymentMethodError").show();

		}else{

			$("#checkoutProceed input[type=hidden][name=paymentMethod]").val(selectedPaymentMethod.val());

			var deliveryTime = $("input[type=radio][name=deliverTime]:checked").val();

			var deliverDay = $("input[type=radio][name=deliverDay]:checked").val();

			$("#checkoutProceed input[type=hidden][name=deliveryTime]").val(deliveryTime + " - " + deliverDay);

			//$("#checkoutProceed").submit();

		}

	});

	$('body').on('click', ".fa-heart", function(){
        var i = $(this);
        i.removeClass('fa-heart').addClass('fa-heart-o');
        $.post(home + "favourite-post/remove",{
            id: i.data('id')
        },
        function(data, status){
            if(data == "login"){
                window.location.href = "{{ route('login') }}";
            }else{
                try {
                    var r = JSON.parse(data);
                    if((r.error ?? true) == true){
                        i.removeClass('fa-heart-o').addClass('fa-heart');
                    }
                }catch (e) {
                    i.removeClass('fa-heart-o').addClass('fa-heart');                
                }
            }
        });
    });
    $('body').on('click', ".fa-heart-o", function(){
        var i = $(this);
        i.removeClass('fa-heart-o').addClass('fa-heart');
        $.post(home + "favourite-post/add",{
            id: i.data('id')
        },
        function(data, status){
            if(data == "login"){
                window.location.href = "{{ route('login') }}";
            }else{
                try {
                    var r = JSON.parse(data);
                    if((r.error ?? true) == true){
                        i.removeClass('fa-heart').addClass('fa-heart-o');
                    }
                }catch (e) {
                    i.removeClass('fa-heart').addClass('fa-heart-o');                
                }
            }
        });
    });

});
function loadOptions(element, url, clear = false, open = false, triggerChange = false, selected = 0){
    if(clear == true){
        element.find('option').remove();
    }
    $.ajax({
        url: url,
        success: function(response){
            var data = JSON.parse(response);
            $.each(data, function(id, item){
                if(element.val() != item.id){
					var isSelected = false;
					if(selected == item.id){
						isSelected = true;
					}
                    element.append(new Option(item.name, item.id, isSelected, isSelected));
                }
            });
            element.select2('close');
            if(open == true){
                element.select2('open');
			}			
			if(triggerChange == true){
				element.trigger('change');
			}
        }
    });
}
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}