var ajaxCallbacks = {
    "simpleDone" : function(response) {
        // This is called by the link attribute "data-on-done" => "simpleDone"
        console.dir(response);
        $("#ajax_result_01").html(response.body);
    },
    "deleteItemDone" : function(response) {
        console.dir(response);
        $("tr[data-key='"+response.id+"'").fadeOut();
    }
}

$(document).ready(function() {
	calculateSubtotal();

	// $('.delete-item').click(toAjax);

	$('#incomingitem-item_id').on("change", function(e) { 
		$.post("?r=incoming-purchase/get-item", {item_id : this.value}, function(response) {
			$('#unitofmeasurement-label').html('('+response.unit_of_measurement+')');
			$('#incomingitem-quantity').focus();
		}, "json");
	});

	$('#incomingitem-item_id').on("select2:unselecting", function(e) { 
		$('#incomingitem-quantity').val(0).change();
		$('#incomingitem-price').val(0);
	});

	$('#incomingitem-quantity').on("keyup", function(e) {
		calculateSubtotal();
	});

	$('#incomingitem-price').on("keyup", function (e) {
		calculateSubtotal();
	});

	function calculateSubtotal() {
		price 		= $('#incomingitem-price').val();
		quantity 	= $('#incomingitem-quantity').val();		
		subtotal 	= (price * quantity);
		if (subtotal == 0) subtotal = "";
		$('#incomingitem-subtotal').val(subtotal);
	}

	$('#incomingitem-subtotal').on("keyup", function (e) {
		calculatePrice();
	});
	function calculatePrice() {
		subtotal 	= $('#incomingitem-subtotal').val();
		quantity 	= $('#incomingitem-quantity').val();
		price 		= (subtotal / quantity);
		$('#incomingitem-price').val(price);
	}
});