/**
 * Override the default yii confirm dialog. This function is
 * called by yii when a confirmation is requested.
 *
 * @param message the message to display
 * @param okCallback triggered when confirmation is true
 * @param cancelCallback callback triggered when cancelled
 */
/*yii.confirm = function (message, okCallback, cancelCallback) {
    swal({
        title: '',
        text: message,
        type: 'warning',
        showCancelButton: true,
        closeOnConfirm: true,
        allowOutsideClick: true
    }, okCallback);
};

krajeeDialog.confirm = function (message, okCallback, cancelCallback) {
    swal({
        title: '',
        text: message,
        type: 'warning',
        showCancelButton: true,
        closeOnConfirm: true,
        allowOutsideClick: true
    }, okCallback);
};*/

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
  
      const negativeSign = amount < 0 ? "-" : "";
  
      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;
  
      return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
      console.log(e)
    }
  };

$(':input[type=text]:enabled:visible').first().focus();
$('.autofocus').focus();

$(document).keydown(function(e) {
    if (e.keyCode == 77 && e.ctrlKey) {
        e.preventDefault();
        $(':input[type=text]:enabled:visible:first').focus();
        $('.autofocus').focus();
    }
});

(function($){
    $.fn.focusTextToEnd = function(){
        this.focus();
        var $thisVal = this.val();
        this.val('').val($thisVal);
        return this;
    }
}(jQuery));

var input;
var submit_form = false;
var filter_selector = '#grid-id-filters input';
var filter_selector2 = '#grid2-id-filters input';

$("body").on('beforeFilter', "#grid-id" , function(event) {
    return submit_form;
});
$("body").on('afterFilter', "#grid-id" , function(event) {
    submit_form = false;
});

$("body").on('beforeFilter', "#grid2-id" , function(event) {
    return submit_form;
});
$("body").on('afterFilter', "#grid2-id" , function(event) {
    submit_form = false;
});

$(document)
.off('keydown.yiiGridView change.yiiGridView', filter_selector)
.on('keyup', filter_selector, function(event) {
    input = $(this).attr('name');

    if(submit_form === false) {
        submit_form = true;
        $("#grid-id").yiiGridView("applyFilter");
    }
    if (event.keyCode === 13) $('input').next().focus().select();
})
.on('pjax:success', function() {
    var i = $("[name='"+input+"']");
	i.focusTextToEnd();
});

$(document)
.off('keydown.yiiGridView change.yiiGridView', filter_selector2)
.on('keyup', filter_selector2, function(event) {
    input = $(this).attr('name');

    if(submit_form === false) {
        submit_form = true;
        $("#grid2-id").yiiGridView("applyFilter");
    }
    if (event.keyCode === 13) $('input').next().focus().select();
})
.on('pjax:success', function() {
    var i = $("[name='"+input+"']");
	i.focusTextToEnd();
});


// Catch the keydown for the entire document
/* $(document).keydown(function(e) {

    // Set self as the current item in focus
    var self = $(':focus'),
        // Set the form by the current item in focus
        form = self.parents('form:eq(0)'),
        focusable;
  
    // Array of Indexable/Tab-able items
    focusable = form.find('input,a,select,button,textarea').filter(':visible');
  
    function enterKey(){
        if (e.which === 13 && !self.is('textarea')) { // [Enter] key

            // If not a regular hyperlink/button/textarea
            // if ($.inArray(self, focusable) && (!self.is('a')) && (!self.is('button'))){
            if ($.inArray(self, focusable) && (!self.is('button'))){
                // Then prevent the default [Enter] key behaviour from submitting the form
                e.preventDefault();
            } // Otherwise follow the link/button as by design, or put new line in textarea

            // Focus on the next item (either previous or next depending on shift)
            focusable.eq(focusable.index(self) + (e.shiftKey ? -1 : 1)).focus().select();

            return false;
        }
    }
    // We need to capture the [Shift] key and check the [Enter] key either way.
    if (e.shiftKey) { enterKey() } else { enterKey() }
}); */

/* $('#myModal').keydown(function(e) {
    if (e.shiftKey) {
        $('#item-selector').click();
    }
});
$('#myModal2').keydown(function(e) {
    if (e.shiftKey) {
        $('#item-selector2').click();
    }
}); */


$('.kv-type-range input').change(function() {
    if ($(this).val() > 100) $(this).val(100);
});

function initFix(){
    $('body').layout('fix');
}
