/*=========================================================================================
		File Name: form-repeater.js
		Description: Repeat forms or form fields
		----------------------------------------------------------------------------------------
		Item Name: Robust - Responsive Admin Theme
		Version: 3.0
		Author: PIXINVENT
		Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

(function(window, document, $) {
	'use strict';

    var count = 0;
	// Default
	$('.repeater-default').repeater({
        // (Optional)
        // start with an empty list of repeaters. Set your first (and only)
        // "data-repeater-item" with style="display:none;" and pass the
        // following configuration flag
        initEmpty: false,
        // (Optional)
        // "defaultValues" sets the values of added items.  The keys of
        // defaultValues refer to the value of the input's name attribute.
        // If a default value is not specified for an input, then it will
        // have its value cleared.
        defaultValues: {
            'text-input': 'foo'
        },
        // (Optional)
        // "show" is called just after an item is added.  The item is hidden
        // at this point.  If a show callback is not given the item will
        // have $(this).show() called on it.
        show: function () {
            // count +=  1;
            // alert(count);
            // $(this).find('.card-header>a.dp').removeAttr('data-target');
            // $(this).find('.card-header>a.dp').attr('data-target','#thebody'+count);
            //
            // $(this).find('.card>.bb').removeAttr('id');
            // $(this).find('.card>.bb').attr('id','thebody'+count);
            //
            // $(this).find('.accordion').removeAttr('id');
            // $(this).find('.accordion').attr('id','the_accordion'+count);
            // $(this).find('.card>.bb').attr('data-parent','#the_accordion'+count);
            $(this).slideDown();
        },
        // (Optional)
        // "hide" is called when a user clicks on a data-repeater-delete
        // element.  The item is still visible.  "hide" is passed a function
        // as its first argument which will properly remove the item.
        // "hide" allows for a confirmation step, to send a delete request
        // to the server, etc.  If a hide callback is not given the item
        // will be deleted.
        hide: function(remove) {
            if (confirm('Are you sure you want to remove this item?')) {
                count -=  1;
                $(this).slideUp(remove);
            }
        },

        // (Optional)
        // Removes the delete button from the first list item,
        // defaults to false.
        isFirstItemUndeletable: true
	});

})(window, document, jQuery);