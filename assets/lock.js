(function(document, window, $) {
    "use strict"

    var defaults = {
        isLocked: false,
        isPanel: false,
        language: null,
        page: null,
        pingInterval: 10000
    }

    function ajax(method, url, next) {
        var request = new XMLHttpRequest();

        request.addEventListener('load', next)
        request.open(method.toLowerCase(), url)
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
        request.send()
    }

    function currentLocation() {
        var pathname = window.location.pathname,
            query = window.location.search

        if (window.app !== undefined) {
            // Remove action segment from the Panel pathname
            pathname = pathname.replace(/\/[a-z]+$/, '')
        }

        return pathname + query
    }

    function extend(target, source) {
        for (var key in source) {
            if (source.hasOwnProperty(key)) target[key] = source[key]
        }

        return target
    }

    function Lock(state) {
        this.state = extend(defaults, state || {})
        this.init()
    }

    extend(Lock.prototype, {
        init: function() {
            this.state.location = currentLocation()

            if (this.isPanel() && this.isLocked()) {
                this.disableForm()
                this.displayLockMessage()
            }

            if (! this.isLocked()) {
                this.lock()
                this.startLockPing()
            }
        },

        disableForm: function() {
            // Remove slug link
            $('.field-name-title .field-help a').parent().remove()

            // Disable all form inputs from main area
            $('.mainbar :input').prop('disabled', true)

            // Disable keyboard shortcuts
            $(document).off('keydown')

            // Remove 'Save' button
            $('.main .form [type=submit]').remove()

            // Remove 'Save' button
            $('.main .form [data-modal]').remove()

            // $('.main .form [data-sortable="true"]').removeProp('datasortable')

            // Remove all action buttons except the 'Preview' one
            $('.sidebar-content .sidebar-list:first a:not([data-shortcut="p"])').parent().remove()
        },

        displayLockMessage: function() {
            // Copy lock message from template
            var message = $('.lock-message')
            $('.main .form').prepend(message.show())
        },

        isLocked: function() {
            return this.state.isLocked
        },

        isPanel: function() {
            return window.app !== undefined
        },

        lock: function() {
            ajax('post', this.getUrl('lock'))
        },

        startLockPing: function() {
            var lockPing = setInterval(function() {
                // Stop this lock ping when the URL has changed
                if (this.state.location !== currentLocation()) {
                    return clearInterval(lockPing)
                }

                this.lock()
            }.bind(this), this.state.pingInterval)
        },

        updateUrl: function() {
            var url;
            url = window.location.pathname.replace(/\/[a-z]+$/, '')
            url = url.replace(/^\/panel\/pages\//, '')

            this.state.page = url
        },

        getUrl: function(action) {
            var base = window.location.origin + '/page-lock/' + action,
            language = this.state.language ? '&language=' + this.state.language : '',
            unique = this.state.unique ? '&unique=' + this.state.unique : ''

            if (this.isPanel()) this.updateUrl()

            return base + '?page=' + this.state.page + language + unique
        }

    })

    // lockState

    window.Lock = Lock

    document.addEventListener('DOMContentLoaded', function() {
        // Run the plugin outside the panel
        if (window.app === undefined) {
            new window.Lock(window.lockState)
        }
    }, false)

})(document, window, jQuery);
