var gross_price = 0;

function retrieveItem(shortcode, customer_id, customer_group) {
	gross_price = 0;

	$('.modal').modal('hide');
	isNewRecord = $('#is_new_record').val();
	$.post("?r=outgoing-sale/get-item-by-shortcode", { item_shortcode: shortcode, customer_id: customer_id }, function (response) {

		if (response.unit_of_measurement) {
			$('#unitofmeasurement-label').html('<br>(' + response.current_quantity + ' ' + response.unit_of_measurement + ')');
		} else {
			$('#unitofmeasurement-label').html('');
		}

		$('#item_shortcode').val(response.shortcode);
		$('#item_name').val(response.name);
		$('#item_brand').val(response.brand);
		$('#item_type').val(response.type);

		$('#outgoingitem-item_id').val(response.id);

		if (isNewRecord) {
			$('#outgoingitem-price').val(Math.round(response.purchase_gross_price));
			$('#outgoingitem-discount').val(0);
		}

		$('#last_price').val(response.lastPrice);

		$('#net_price').html(formatMoney(response.purchase_net_price));
		$('#gross_price').html(formatMoney(response.purchase_gross_price));
		gross_price = response.purchase_gross_price;

		$('.price-groups').val('');

		response.price_groups.forEach(price_group => {
			if (customer_group == price_group.priceGroup.name && isNewRecord) {
				$('#outgoingitem-price').val(Math.round(price_group.price));
				$('#outgoingitem-discount').val(parseFloat(price_group.discount).toFixed(2));
			}
			$('#' + price_group.priceGroup.name + '-discount').html(price_group.discount+'%');
			$('#' + price_group.priceGroup.name + '-price').html(formatMoney(price_group.price));
		});

		$('#incomingitem-quantity').focus();

		if (response.isFound) { 
			$('#outgoingitem-quantity').focus();
		} else {
			$('#item_shortcode').focus();
		}
	}, "json");
}

$(document).ready(function() {
	$('#item_shortcode').focus();
	
	isNewRecord = $('#is_new_record').val();
	if (!isNewRecord) {
		retrieveItem($('#item_shortcode').val(), $('#outgoing-customer_id').val(), $('#customer_group').val());
	}
	calculateSubtotal();

	if ($('#item_shortcode').val()) retrieveItem($('#item_shortcode').val(), $('#outgoing-customer_id').val(), $('#customer_group').val());

	$('#item_shortcode').on("change", function (e) {
		retrieveItem(this.value, $('#outgoing-customer_id').val(), $('#customer_group').val());
	});
	
	/* $('#outgoingitem-item_id').on("change", function(e) { 
		$.post("?r=outgoing-sale/get-item", { item_id: this.value }, function (response) {
			$('#unitofmeasurement-label').html('(' + response.unit_of_measurement + ')');
			$('#outgoingitem-price').val(response.price);
			$('#outgoingitem-quantity').focus();
		}, "json"); 
	}); */

	/* $('#outgoingitem-item_id').on("select2:unselecting", function(e) { 
		$('#outgoingitem-quantity').val(0).change();
		$('#outgoingitem-price').val(0);
	}); */

	$('#outgoingitem-quantity').on("keyup", function(e) {
		calculateSubtotal();
	});

	function calculateSubtotal() {
		price 		= $('#outgoingitem-price').val();
		quantity 	= $('#outgoingitem-quantity').val();		
		subtotal 	= (price * quantity);
		if (subtotal == 0) subtotal = "";
		$('#outgoingitem-subtotal').val(Math.round(subtotal));
	}

	$('#outgoingitem-price').on("keyup", function (e) {
		calculateDiscount();
	});
	function calculateDiscount() {
		price 		= $('#outgoingitem-price').val();
		discount 	= ((gross_price - price) / gross_price) * 100;
		$('#outgoingitem-discount').val(parseFloat(discount).toFixed(2));
		calculateSubtotal();
	}

	$('#outgoingitem-discount').on("keyup", function (e) {
		calculateNetPrice();
	});
	function calculateNetPrice() {
		discount 	= $('#outgoingitem-discount').val();
		price 		= gross_price - (discount/100 * gross_price);
		$('#outgoingitem-price').val(Math.round(price));
		calculateSubtotal();
	}

	$('#outgoingitem-subtotal').on("keyup", function (e) {
		calculatePrice();
	});
	function calculatePrice() {
		subtotal 	= $('#outgoingitem-subtotal').val();
		quantity 	= $('#outgoingitem-quantity').val();
		price 		= (subtotal / quantity);
		$('#outgoingitem-price').val(Math.round(price));
		calculateSubtotal();
	}
});


$('#myModal').on('hidden.bs.modal', function () {
	$('#outgoingitem-quantity').focus();
	$('#myModal input').val('');
});
$('#myModal').on('shown.bs.modal', function () {
	$('#myModal input').first().focus();
});

$('#myModal2').on('hidden.bs.modal', function () {
	$('#outgoingitem-quantity').focus();
	$('#myModal2 input').val('');
});
$('#myModal2').on('shown.bs.modal', function () {
	$('#myModal2 input').first().focus();
});

$(document).keydown(function(e) {
	if (e.keyCode == 112 ) { // F1
		console.log(e);
        e.preventDefault();
        $('#myModal2').modal('hide');
        $('#myModal').modal('show');
	}	
	if (e.keyCode == 113 ) { // F2
		console.log(e);
        e.preventDefault();
        $('#myModal2').modal('show');
        $('#myModal').modal('hide');
    }
});



$('#outgoing-customer_id').on("change", function(e) { 
	$.post("?r=outgoing-sale/get-customer", { customer_id: this.value }, function (response) {
		$('#outgoing-salesman_id').val(response.salesman_id).change();
		// $('#outgoingitem-quantity').focus();
	}, "json"); 
});

$('#outgoing-customer_id').on("select2:unselecting", function(e) { 
	$('#outgoing-salesman_id').val(0).change();
});

