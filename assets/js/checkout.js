$(function () {

    // VARIABLES
    var parametersCheckout = $('#parameters-checkout');
    var inputAmount = $('#recarga-amount');
    var checkoutButton = $('#checkoutButton');

    // RECARGA-AMOUNT CHANGE
    checkoutButton.click(function () {
        var amountValue = inputAmount.val();
        crearInputs(amountValue);
    });

    // CREAR INPUT
    function crearInputs(amount) {
        parametersCheckout.html('');
        $.ajax({
            dataType: 'json',
            method: 'POST',
            url: 'checkoutController.php',
            data: {
                'sub_check': '',
                'amount': amount
            },
            success: function (result) {
                var jsonResult = result;
                if (jsonResult.hasOwnProperty('message')) {
                    modalError(jsonResult.message);
                } else {
                    var inputCheckout = '<input name="merchantId" type="hidden" value="508029">\
                    <input name="accountId" type="hidden" value="512321">\
                    <input name="description" type="hidden" value="' + jsonResult.description + '">\
                    <input name="referenceCode" type="hidden" value="' + jsonResult.referenceCode + '">\
                    <input name="amount" type="hidden" value="' + jsonResult.amount + '">\
                    <input name="tax" type="hidden" value="0">\
                    <input name="taxReturnBase" type="hidden" value="0">\
                    <input name="currency" type="hidden" value="COP">\
                    <input name="signature" type="hidden" value="' + jsonResult.signature + '">\
                    <input name="buyerEmail" type="hidden" value="' + jsonResult.buyerEmail + '">\
                    <input name="extra1" type="hidden" value="' + jsonResult.user + '">\
                    <input name="test" type="hidden" value="1">\
                    <input name="responseUrl" type="hidden" value="http://smsenvoice.com/SMS/app/checkout/respuesta.php">\
                    <input name="confirmationUrl" type="hidden" value="http://smsenvoice.com/SMS/app/checkout/confirmacion.php">';
                    parametersCheckout.append(inputCheckout);
                    $('form').submit();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var mensaje = 'Ha ocurrido un error, por favor contacte a su proveedor';
                modalError(mensaje);
            }

        });
    }

    // DISEÑO DEL MENSAJE
    function modalError(mensaje) {
        var containerModal = $('#modalErrorContainer');
        containerModal.html('');
        var modal = '<!-- BEGIN: Custom modal -->\
        <div class="modal fade modal-danger" id="errorModal">\
            <div class="modal-dialog" role="document">\
                <div class="modal-content">\
                    <div class="modal-header">\
                        <h4 class="modal-title">Error</h4>\
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                            <span aria-hidden="true">×</span>\
                        </button>\
                    </div>\
                    <div class="modal-body">\
                        '+ mensaje +'\
                    </div>\
                    <div class="modal-footer">\
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>\
                    </div>\
                </div>\
            </div>\
        </div>\
        <!-- END: Custom modal -->';
        containerModal.append(modal);
        $('#errorModal').modal('show');
    }
});