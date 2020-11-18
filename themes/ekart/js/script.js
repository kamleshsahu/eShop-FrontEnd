$(document).ready(function(e){
    $('.variant .btn').click(function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var v = $(this).find('input[type=radio][name=options]').data();
        $("#child_" + id).val(v.id);
        $('#qtyPicker_' + id).attr('data-max', v.stock);
        $('#qtyPicker_' + id).attr('max', v.stock).change();
        if(v.mrp != ""){
            $('#price_mrp_' + id).show().find('.value').html(v.mrp);
            $('#price_savings_' + id).show().find('.value').html(v.savings);
            $("#price_offer_" + id).show().find('.value').html(v.price).show();
            $('#price_regular_' + id).hide();
        }else{
            $('#price_mrp_' + id).hide();
            $('#price_savings_' + id).hide();
            $('#price_offer_' + id).hide();
            $('#price_regular_' + id).show().find('.value').html(v.price);
        }
    });
    $('form').each(function(index, value) {
        $(this).find('.variant .btn:first').click();
	});
	$(".qtyPicker").change(function(){
		if(parseInt($(this).val()) < 1){
			$(this).val(1);
		}
	});

	$("select[name=varient]").change(function(e){
		var id = $(this).data('id');
		var selected = $(this).find('option:selected');
		$("#price_" + id).html(selected.data('price'));
		$("#mrp_" + id).html(selected.data('mrp'));
		$("#savings_" + id).html(selected.data('savings'));
	});
});

