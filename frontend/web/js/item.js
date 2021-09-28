function calculatePriceGroup(base, group) {
	
	default_price 	= $('#item-purchase_gross_price').val();
	discount 		= parseFloat($('#'+group+'-discount').val());
	price 			= parseFloat($('#'+group+'-price').val());
	
	if (base == "discount") $('#' + group + '-price').val(eval(default_price) - eval(discount/100*default_price));
	if (base == "price") {
		calculated_discount = (default_price - price) / default_price * 100;
		if (eval(calculated_discount) < 0) calculated_discount = 0;
		$('#' + group + '-discount').val(calculated_discount);
	}
}

function calculateGrossPrice() {

	net_price = parseFloat($('#item-purchase_net_price').val());
	discount = parseFloat($('#item-purchase_discount').val());

	gross_price = eval(net_price) + (eval(discount)/100 * eval(net_price));
	$('#item-purchase_gross_price').val(gross_price);
}