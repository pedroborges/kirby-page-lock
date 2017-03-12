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
            var form = $('.mainbar .form')

            // Remove slug link
            form.find('.field-name-title .field-help a')
                .contents()
                .unwrap()
                .wrap('<span style="padding-left:.5em"></span>')

            // Disable all form inputs from main area
            form.find('.field').addClass('field-is-readonly field-is-disabled')
            form.find('.field :input').prop('readonly', true).prop('disabled', true)

            // Disable textarea buttons
            form.find('.field-buttons').remove()

            // Disable keyboard shortcuts
            $(document).off('keydown')

            // Remove 'Save' and 'Discard' button
            form.find('.buttons.buttons-centered, :submit').remove()

            // Remove structure options
            form.find('.structure-entry-options, .structure-table-options').remove()
            form.find('.structure-entries [data-modal]')
                .contents()
                .unwrap()
                .wrap('<span style="display:block;padding:.5em;overflow:hidden;text-overflow:ellipsis;"></span>')

            // Remove modal links
            form.find('[data-modal]').remove()

            // Remove all action buttons except the 'Preview' one
            $('.sidebar-content .sidebar-list:first a:not([data-shortcut="p"])').parent().remove()

            // Remove 'Files' from sidebar
            var files = $('.sidebar-content .hgroup-title a[href$="files"]').parent().parent()
            files.next().remove()
            files.remove()

            // Disable UI sorting
            form.find('.ui-sortable, .structure-entries, .structure-table tbody').sortable({disabled: true})
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
            uniqueId = this.state.uniqueId ? '&uniqueId=' + this.state.uniqueId : ''

            if (this.isPanel()) this.updateUrl()

            return base + '?page=' + this.state.page + language + uniqueId
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
