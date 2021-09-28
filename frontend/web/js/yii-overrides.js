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


$(function() { //shorthand document.ready function
    /* $('form').on('submit', function(e) { //use on if jQuery 1.7+
        $('input[type=submit]').attr('disabled', true);
        $('button[type=submit]').attr('disabled', true);
    }); */

    $('a[href^="http"]').click(function() {
        $(this).attr('disabled', true);
    });
});