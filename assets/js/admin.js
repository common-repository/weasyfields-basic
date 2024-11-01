jQuery(function()
{
    "use strict";
    jQuery(document).ready(function ()
    {    
        var fieldList = jQuery("#weasyfields-field-list");
        function WeasyFieldsSorter()
        {
            return new Promise((resolve, reject) => {
                let fieldItemLength = fieldList.find(".weasyfields-field-item").length;
                for (let fieldItemIndex = 0; fieldItemIndex < fieldItemLength; fieldItemIndex++) {
                    let currentField = fieldList.children(".weasyfields-field-item").eq(fieldItemIndex);
                    let inputList = currentField.find("input[data-name],textarea[data-name],select[data-name]");
                    let inputListLength = inputList.length;
                    for (let inputIndex = 0; inputIndex < inputListLength; inputIndex++) {
                        let dataName = inputList.eq(inputIndex).attr("data-name");
                        let newName = "fields["+fieldItemIndex+"]"+dataName+"";
                        inputList.eq(inputIndex).attr("name",newName);
                        if ( inputIndex ===  inputListLength - 1 ) {
                            resolve("done");
                        }
                    }
                }
            });
        }

        jQuery(fieldList).sortable({
            handle: '.field-item-handle',
            placeholder: 'sortable-placeholder',
            opacity: 0.8
        });
        
        jQuery(fieldList).disableSelection();

        let weasyFieldsNullMsg = jQuery("#weasyfields-fields-form").attr("data-null-msg");
        if ( undefined !== weasyFieldsNullMsg ) {
            weasyFieldsNullMsg = weasyFieldsNullMsg.trim();
        }

        const WeasyFieldsİtemRemove = ( item, delay = 600, delayPlus = 50 ) => 
        {
            this.delay = delay;
            item.css({
                opacity: 1.0, 
                visibility: "visible"
            }).animate({
                 opacity: 0
            }, this.delay, () => {
                item.remove();
            });
            this.run = callback => {
                setTimeout( ( () => { 
                    callback(); 
                } ).bind( this ) , this.delay + delayPlus );
            };
            return this;
        };

        jQuery(document).on( 'click', '.weasyfields-field-open-menu', function(e)
        {
            e.preventDefault();
            let fieldMenuOpen = jQuery(this);
            let status = fieldMenuOpen.attr("data-status");
            let firstParent = fieldMenuOpen.parent().parent().parent();
            if ( status === "closed" ) {
                firstParent.next('.menu-item-settings').slideDown(250);
                fieldMenuOpen.attr("data-status","opened");
                fieldMenuOpen.addClass("opened");
            } else {
                firstParent.next('.menu-item-settings').slideUp(250);
                fieldMenuOpen.attr("data-status","closed");
                fieldMenuOpen.removeClass("opened");
            }
        });

        jQuery(document).on( 'click', '#weasyfields-add-field', function()
        {
            let fieldType = jQuery("#weasyfields-field-type").val();
            let field = jQuery("#weasyfields-field-type-list #weasyfields-list-"+fieldType).html();
            jQuery(".weasyfields-null-msg").remove();
            jQuery(fieldList).append(field);
        });

        jQuery(document).on( 'click', '.weasyfields-remove-field', function(e)
        {
            e.preventDefault();
            let currentField = jQuery(this).parent().parent();
            WeasyFieldsİtemRemove( currentField, 300 ).run(function(){
                let fieldLength = jQuery(fieldList).find("li").length;
                if ( 0 === fieldLength ) {
                    jQuery(fieldList).html('<div class="weasyfields-null-msg">'+weasyFieldsNullMsg+'</div>');
                }
            });
        });

        jQuery(document).on( 'click', '.weasyfields-add-opt', function(e)
        {
            e.preventDefault();
            let currentİtem = jQuery(this);
            let optionsList = currentİtem.next(".weasyfields-field-item-opt-wrapper");
            let optionsİtem = optionsList.children(".weasyfields-field-item-opt").eq(0).html();
            optionsİtem.replace( ' weasyfields-required', '' );
            optionsİtem = '<li class="weasyfields-field-item-opt">'+optionsİtem+'<span class="weasyfields-field-item-opt-remove"></span></li>';
            optionsList.append(optionsİtem);
            optionsList.children(".weasyfields-field-item-opt:last-child").children('input').val('');
        });

        
        jQuery(document).on( 'click', '.weasyfields-field-item-opt-remove', function(e)
        {
            e.preventDefault();
            let currentField = jQuery(this).parent();
            WeasyFieldsİtemRemove( currentField, 300 );
        });

        jQuery(document).on( 'change', '.weasyfields-checkbox-true-false', function()
        {
            if ( jQuery(this).is(':checked') ) {
                jQuery(this).prev(".weasyfields-checkbox-value").val("true");
            } else {
                jQuery(this).prev(".weasyfields-checkbox-value").val("false");
            }        
        });

        jQuery(document).on( 'keyup', '.weasyfields-field-label', function()
        {
            let currentİtem = jQuery(this);
            let currentİtemParent = currentİtem.parent().prev(".menu-item-bar");
            currentİtemParent = currentİtemParent.find(".weasyfields-title");
            currentİtemParent.children("span").text(currentİtem.val());
        });

        function WeasyFieldsErrorShow( errorClass )
        {
            jQuery(errorClass).addClass("notice");
            jQuery(errorClass).addClass("notice-error");
            jQuery(errorClass).addClass("weasyfields-error-show");
        }

        function WeasyFieldsErrorHide( errorClass )
        {
            jQuery(errorClass).removeClass("notice");
            jQuery(errorClass).removeClass("notice-error");
            jQuery(errorClass).removeClass("weasyfields-error-show");
        }

        jQuery(document).on( 'keyup', '.weasyfields-input-null-dedector', function()
        {
            if ( "" !== jQuery(this).val() ) {
                jQuery(this).removeClass("weasyfield-required");
            }
            let nullFields = jQuery(this).closest(".weasyfields-field-item").find(".menu-item-settings").find(".weasyfield-required").length;
            if ( 0 === nullFields ){
                jQuery(this).closest(".weasyfields-field-item").find(".menu-item-handle").removeClass("weasyfield-required");
            }
            nullFields = jQuery("#weasyfields-fields-form").find(".weasyfield-required").length;
            if ( 0 === nullFields ){
                WeasyFieldsErrorHide(".weasyfields-null-error");
            }
        });

        jQuery(document).on( 'click', '#weasyfields-save-or-update-action', async function(e)
        {
            e.preventDefault();
            let form = jQuery("#weasyfields-fields-form");

            let dedectors = form.find(".weasyfields-input-null-dedector");
            jQuery.each( dedectors, function ( index, val ) {
                let currentDedector = dedectors.eq(index);
                if ( "" === currentDedector.val() ) {
                    currentDedector.addClass("weasyfield-required");
                    currentDedector.closest(".weasyfields-field-item").find(".menu-item-handle").addClass("weasyfield-required");
                }
            });

            let fieldsLength = fieldList.find("li").length;
            if ( 0 === fieldsLength ) {
                jQuery(".weasyfields-null-msg").addClass("weasyfield-required");
            } else {
                // Sorter
                await WeasyFieldsSorter();
            }

            let nullFields = form.find(".weasyfield-required").length;
            if ( 0 !== nullFields ) {
                WeasyFieldsErrorShow(".weasyfields-null-error");
            } else {
                form.submit();
            }
        });

        jQuery(document).on( 'change', '.weasyfields-validation', function(e)
        {
            let currentSelect = jQuery(this);
            let optionValue = currentSelect.val();
            if ( "phone" === optionValue ) {
                currentSelect.next(".weasyfields-phone-pattern").show();
                currentSelect.next(".weasyfields-phone-pattern").find("input").addClass("weasyfields-input-null-dedector");
            } else {
                currentSelect.next(".weasyfields-phone-pattern").hide();
                currentSelect.next(".weasyfields-phone-pattern").find("input").removeClass("weasyfields-input-null-dedector");
            }
        });

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

    });
    
});