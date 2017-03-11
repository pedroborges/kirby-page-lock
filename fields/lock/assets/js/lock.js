(function(document, window, $) {
    "use strict"

    $.fn['lock'] = function() {
        var state = $(this[0]).data('state')
        new window.Lock(state)
    }

})(document, window, jQuery);
