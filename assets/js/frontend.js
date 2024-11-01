jQuery(function()
{
    "use strict";
    jQuery(document).ready(function ()
    {    
        let selectElements = jQuery(document).find(".weasyfields-select-option-value");
        jQuery.each( selectElements, function( index, val )
        {
            let currentSelect = selectElements.eq(index);
            let optionValue = currentSelect.attr("data-option");
            currentSelect.children("option[value='"+optionValue+"']").prop('selected', true);
            if ( "phone" === optionValue ) {
                currentSelect.next(".weasyfields-phone-pattern").show();
            }
        });

        jQuery(".woocommerce-MyAccount-content form").attr( 'enctype', 'multipart/form-data' );
    });
    
});