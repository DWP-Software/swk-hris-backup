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

	$('#outgoingitem-item_id').on("change", function(e) { 
		$.post("?r=outgoing-sale/get-item", { item_id: this.value }, function (response) {
			$('#unitofmeasurement-label').html('(' + response.unit_of_measurement + ')');
			$('#outgoingitem-price').val(response.price);
			$('#outgoingitem-quantity').focus();
		}, "json"); 
	});

	$('#outgoingitem-item_id').on("select2:unselecting", function(e) { 
		$('#outgoingitem-quantity').val(0).change();
		$('#outgoingitem-price').val(0);
	});

	$('#outgoingitem-quantity').on("keyup", function(e) {
		calculateSubtotal();
	});

	$('#outgoingitem-price').on("keyup", function (e) {
		calculateSubtotal();
	});

	function calculateSubtotal() {
		price 		= $('#outgoingitem-price').val();
		quantity 	= $('#outgoingitem-quantity').val();		
		subtotal 	= (price * quantity);
		if (subtotal == 0) subtotal = "";
		$('#outgoingitem-subtotal').val(subtotal);
	}

	$('#outgoingitem-subtotal').on("keyup", function (e) {
		calculatePrice();
	});
	function calculatePrice() {
		subtotal 	= $('#outgoingitem-subtotal').val();
		quantity 	= $('#outgoingitem-quantity').val();
		price 		= (subtotal / quantity);
		$('#outgoingitem-price').val(price);
	}
});