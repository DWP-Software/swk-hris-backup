
function retrieveItem(shortcode) {
	$('.modal').modal('hide');
	isNewRecord = $('#is_new_record').val();
	$.post("?r=order/get-item-by-shortcode", { item_shortcode: shortcode }, function (response) {

		if (isNewRecord) {
			$('#orderitem-item_shortcode').val(response.shortcode);
			$('#orderitem-item_name').val(response.name);
			$('#orderitem-brand_storage').val(response.brand);
			$('#orderitem-brand_supplier').val(response.brand);
			$('#orderitem-type').val(response.type);
			$('#orderitem-unit_of_measurement').val(response.unit_of_measurement);
		}
		
		$('#current_quantity').html(response.current_quantity);
		$('#total_order_quantity').html(response.total_order_quantity);
		$('#total_to_be_ordered').html(response.total_to_be_ordered);

		$('#orderitem-quantity').focus();

		if (response.isFound) { 
			$('#orderitem-quantity').focus();
		} else {
			$('#orderitem-item_shortcode').focus();
		}
	}, "json");
}

function retrieveTotalToBeOrdered(shortcode, supplier_id) {
	$.post("?r=order/get-total-to-be-ordered", { item_shortcode: shortcode, supplier_id: supplier_id }, function (response) {
		$('#total_to_be_ordered').html(response.total_to_be_ordered);
	}, "json");
}


$(document).ready(function() {
	isNewRecord = $('#is_new_record').val();
	if (!isNewRecord) {
		retrieveItem($('#orderitem-item_shortcode').val());
	}
	calculateSubtotal();

	if ($('#orderitem-item_shortcode').val()) retrieveItem($('#orderitem-item_shortcode').val());

	$('#orderitem-item_shortcode').on("change", function (e) {
		retrieveItem(this.value);
	});

	$('#orderitem-supplier_id').on("change", function (e) {
		retrieveTotalToBeOrdered($('#orderitem-item_shortcode').val(), this.value);
	});
	
	/* $('#orderitem-item_id').on("change", function(e) { 
		$.post("?r=outgoing-sale/get-item", { item_id: this.value }, function (response) {
			$('#unitofmeasurement-label').html('(' + response.unit_of_measurement + ')');
			$('#orderitem-price').val(response.price);
			$('#orderitem-quantity').focus();
		}, "json"); 
	}); */

	$('#orderitem-item_id').on("select2:unselecting", function(e) { 
		$('#orderitem-quantity').val(0).change();
		$('#orderitem-price').val(0);
	});

	$('#orderitem-quantity').on("keyup", function(e) {
		if ($('#orderitem-to_be_ordered').val() == '') $('#orderitem-to_be_ordered').val($('#orderitem-quantity').val());
		calculateSubtotal();
	});

	$('#orderitem-price').on("keyup", function (e) {
		calculateSubtotal();
	});

	function calculateSubtotal() {
		price 		= $('#orderitem-price').val();
		quantity 	= $('#orderitem-quantity').val();		
		subtotal 	= (price * quantity);
		if (subtotal == 0) subtotal = "";
		$('#orderitem-subtotal').val(Math.round(subtotal));
	}

	$('#orderitem-price').on("keyup", function (e) {
		calculateGross();
	});
	function calculateGross() {
		price = $('#orderitem-price').val();
		quantity = $('#orderitem-quantity').val();
		price = (price / quantity);
		$('#orderitem-gross').val(gross);
	}

	$('#orderitem-subtotal').on("keyup", function (e) {
		calculatePrice();
	});
	function calculatePrice() {
		subtotal 	= $('#orderitem-subtotal').val();
		quantity 	= $('#orderitem-quantity').val();
		price 		= (subtotal / quantity);
		$('#orderitem-price').val(Math.round(price));
	}
});




$('#myModal').on('hidden.bs.modal', function () {
	$('#orderitem-quantity').focus();
	$('#myModal input').val('');
});
$('#myModal').on('shown.bs.modal', function () {
	$('#myModal input').first().focus();
});

$(document).keydown(function(e) {
	if (e.keyCode == 112 ) { // F1
		console.log(e);
        e.preventDefault();
        $('#myModal').modal('show');
    }
});
