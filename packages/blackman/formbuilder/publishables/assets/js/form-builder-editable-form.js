function showSubForm_0(key) {
    $("[id^='sc_0_']").hide().find('input, textarea, button, select').prop("disabled", true);
    if (key > 0){
        $('#sc_0_'+key).show().find('input, textarea, button, select').prop("disabled", false);
    }
}

function showSubForm_1(key) {
    $("[id^='sc_1_']").hide().find('input, textarea, button, select').prop("disabled", true);
    if (key > 0){
        $('#sc_1_'+key).show().find('input, textarea, button, select').prop("disabled", false);
    }
}

function showSubForm_2(key) {
    $("[id^='sc_2_']").hide().find('input, textarea, button, select').prop("disabled", true);
    if (key > 0){
        $('#sc_2_'+key).show().find('input, textarea, button, select').prop("disabled", false);
    }
}
