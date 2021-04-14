jQuery(document).ready(function() {
    jQuery('.customizer-sortable-general-control-droppable').sortable({
        update: function() {
            icare_customizer_sortable_refresh_general_control_values();
        }
    });

    function icare_customizer_sortable_refresh_general_control_values() {
        'use strict';
        var values = [];
        jQuery('.icare-customizer-sortable-general-control-sortable-container').find('.icare-section-id').each(function() {
            var value = jQuery(this).val();
            if (value !== '') {
                values.push(value);
            }
        });
        jQuery('.icare-customizer-sortable-general-control-sortable').find('.icare-customizer-sortable-colector').val(JSON.stringify(values));
        jQuery('.icare-customizer-sortable-general-control-sortable').find('.icare-customizer-sortable-colector').trigger('change');
    }
});