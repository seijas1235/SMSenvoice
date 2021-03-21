$(function () {
    // VALIDATE
    $('form').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: 'Por favor ingresa un usuario',
                email: 'Coloque un email válido'
            }
        }
    });
});