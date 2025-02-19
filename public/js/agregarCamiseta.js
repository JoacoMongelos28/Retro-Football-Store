function habilitarInput() {
    ['xs', 's', 'm', 'l', 'xl', 'xxl'].forEach(talle => {
        let checkbox = $('#' + talle);
        let cantidadInput = $('#cantidad' + talle.toUpperCase());

        if (checkbox.prop('checked')) {
            cantidadInput.show();
        } else {
            cantidadInput.hide().val('');
        }
    });
}