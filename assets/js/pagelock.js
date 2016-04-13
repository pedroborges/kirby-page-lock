(function($) {

  /**
   * Page Lock Field for Kirby CMS
   *
   * @version   0.1.0
   * @author    Pedro Borges <oi@pedroborg.es>
   * @copyright Pedro Borges <oi@pedroborg.es>
   * @link      https://github.com/pedroborges/kirby-pagelock
   * @license   <https://github.com/pedroborges/kirby-pagelock/blob/master/license.md>
   */

  $.fn.pagelock = function() {
    var location, isLocked;
    var fieldType = $(this).data('field');
    var fieldName = $(this).data('name');
    var message   = $(this).data('message');
    var lockTime  = $(this).data('lock-time') * 1000;
    var pingTime  = $(this).data('ping-time') * 1000;

    $(app.content.form()).hide();
    $('.sidebar').hide();

    var currentLocation = function() {
      var pathname = window.location.pathname.replace(/\/[a-z]+$/, '');
      var query    = window.location.search;
      return pathname + query;
    }

    var makeUrl = function(action) {
      var path     = '/field/' + fieldName + '/' + fieldType + '/' + action;
      var origin   = window.location.origin;
      var pathname = window.location.pathname.replace(/\/[a-z]+$/, path);
      var query    = window.location.search;
      return origin + pathname + query;
    }

    var getLockStatus = function(next) {
      $.getJSON(makeUrl('status')).success(function(status) {
        isLocked = status;
        next();
      });
    }

    var lock = function() {
      $.post(makeUrl('lock')).success(function() {
        isLocked = true;
      })
    }

    var showMessage = function() {
      $('.mainbar .section').prepend('<span>' + message + '</span>');
    }

    var setup = function() {
      location = currentLocation();

      if (! isLocked) {
        $(app.content.form()).show();
        $('.sidebar').show();
        lock();

        // Keep telling the server we're still editing
        var keepLocking = setInterval(function() {
          if (location != currentLocation()) {
            return clearInterval(keepLocking);
          }

          lock();
        }, lockTime);
      } else {
        $(app.content.form()).remove();
        $('.sidebar').hide();
        showMessage();

        // Let me edit this page when it is unlocked
        var isUnlocked = setInterval(function() {
          if (location != currentLocation()) {
            return clearInterval(isUnlocked);
          }

          getLockStatus(function() {
            if (! isLocked) {
              clearInterval(isUnlocked);
              app.content.reload();
            }
          })
        }, pingTime);
      }
    }

    getLockStatus(function() {
      setup();
    })
  }

})(jQuery);
