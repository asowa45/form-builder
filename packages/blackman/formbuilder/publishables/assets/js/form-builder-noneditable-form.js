$("document").ready(function () {

    $('input[type="radio"].showSubForm_0:checked').each(function () {
        var $this = $(this), key = $this.data('subformid');
        if (key > 0){
            $('#sc_0_'+key).show();
        }
    });

    $('input[type="radio"].showSubForm_1:checked').each(function () {
        var $this = $(this), key = $this.data('subformid');
        if (key > 0){
            $('#sc_1_'+key).show();
        }
    });

    $('input[type="radio"].showSubForm_2:checked').each(function () {
        var $this = $(this), key = $this.data('subformid');
        if (key > 0){
            $('#sc_2_'+key).show();
        }
    });
});