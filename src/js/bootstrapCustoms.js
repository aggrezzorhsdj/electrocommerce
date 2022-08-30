import 'bootstrap-input-spinner/src/bootstrap-input-spinner';

(function ($) {
    $(document).ready(function(){
        const config = {
            groupClass: 'ec-button-number',
        };
        $("input[type='number']").inputSpinner(config);
    });
})(jQuery)
