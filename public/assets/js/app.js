/**
 * Core control center of the entire "app" namespace
 */
var app = (function (app, $) {
    "use strict";
    /**
     * Global variables, such as app cache
     */
    function initCoreVars() {
        app.ui = {};
        app.registry = $({});
        app.cache = (app.cache || {});
        app.body = $('body');
        app.content = $('#holdingPattern');
        app.dialog = $('#dialog');

        var o = $({});

        app.subscribe = function () {
            o.on.apply(o, arguments);
        };

        app.unsubscribe = function () {
            o.off.apply(o, arguments);
        };

        app.publish = function () {
            o.trigger.apply(o, arguments);
        };

    }
    /**
     * Modify the global DOM if needed
     */
    function initCoreDom() {
        return;
    }

    function ajaxSubmit(event) {
        event.preventDefault();
        var form, url, data, callback, container;
        form = $(event.target);
        url = form.attr('action');
        data = form.serialize();
        container = form.parent('div');
        app.util.startThinking(container);
        callback = form.data('callback');
        $.post(url, data).done(function (data) {
            if (callback) {
                callback(data);
            }
            app.util.alertUser('Form submitted successfully');
        }).error(function (data) {
            app.util.alertUser(data);
            app.util.alertUser('Error submitting form');
        }).always(function (data) {
            container.empty().append(form);
        });
    }
    /**
     * Attach global events such as tooltips and dialogs
     */
    function initCoreEvents() {
        /**
         * monitor the viewport for changes, publish new width when it does
         */
        $(window).resize(app.util.debounce(function () {
            app.util.publish('window/resize', app.body.width());
        }, 250));
        /**
         * basic tooltips
         */
        app.body.find('.tooltip').tooltip();
        app.body.find('form').each(function (e) {
 //           app.util.form(this);
        });

        app.util.initMarkup(app.body);

        /**
         * system-wide dialog
         */
        app.body.on('click', '.dialog-load', function (event) {
            event.preventDefault();
            var link, args = {target: 'dialog'};
            link = $(this);
            if (!link.hasClass('dialog-load')) {
                link = link.parent('.dialog-load');
            }
            args.element = link;
            args.url = link.attr('href');
            args.title = (link.attr('title') || link.text());
            app.util.loadContent(args);
        })
            .on('click', 'a.ajaxLink', function (event) {
                var link, args;
                link = $(this);
                if (link.hasClass('glyphicon')) {
                    link = link.parent('a');
                }
                args = {
                    url: link.attr('href')
                };
                app.util.load(args);
            })
            .on('click', 'a.link-ajax-content', function (eve) {
                eve.preventDefault();
                var link = $(this);
                if (!link.hasClass('link-ajax-content')) {
                    link = link.parents('a.link-ajax-content, button.link-ajax-content');
                }
                app.util.loadContent({url: link.attr('href'), target: 'div.content'});
            })

            .on('click', 'a.load-content, button.load-content', function (event) {
                var link, args = {};
                event.preventDefault();
                link = $(event.target);
                if (!link.is('a,button')) {
                    link = link.parents('a.load-content, button.load-content');
                }
                args.url = (link.data('source') || link.attr('href'));
                args = $.extend(args, link.data());
                if (undefined === args.element) {
                    args.element = link;
                }
                console.log(link.data());
                console.log(args);
                app.util.loadContent(args);
            })
            .on('submit', 'form.form-ajax', app.util.ajaxSubmit)
            .end();
        app.body.find('[data-toggle="tooltip"]').tooltip();
    }
    /**
     * Module, App, Page specific modules.
     *  To use:
     *  1: Include the custom js file on page load. (ie: app.tracker.js)
     *  2: Make sure the custom js file has a namespaced init function that fires it off (ie: app.tracker.init();)
     *  3: Add this inline script to the desired page's markup: (using tracker as an example)
     *      <script>app.modules.setContext({"namespace":"tracker"});</script>
     *      - this will fire off the app.tracker.init() function at the right place in the
     *          core app's load so all app.ui, app.util, and other core app functionality is
     *          available to the custom module
     *
     * @return {nothing}
     */
    function initModules() {
        var namespace = app.modules.ns, key = '';
        if (namespace && app[namespace] && app[namespace].init) {
            app[namespace].init();
        }
        for (key in app.modules) {
            if (app.modules.hasOwnProperty(key) && app.modules[key].initOnLoad && app.modules[key].init) {
                app.modules[key].init();
                app.modules[key].initialized = true;
            }
        }
        return;
    }
    /**
     * This is the master init() method, used to fire off the entire app (core,modules,themes,etc..)
     * We segregate out this function so the base "app" namespace can be extended as much as needed before
     * "appGlobal" extends it with a final, organized init() method.
     * @type {Object}
     */
    var appGlobal = {
        init: function () {
            initCoreVars();
            initCoreDom();
            initCoreEvents();
            initModules();
        }
    };
    /**
     * return the final, organized app namespace with everything ready to init();
     */
    return $.extend(app, appGlobal);

}(((undefined !== window.app) ? window.app : {}), jQuery));
/**
 * Create object to store custom page & app specific scripts. Created outside the core app so it's available if
 *  the core app is not ready.
 * @param  {Object} app
 * @param  {jquery} $
 * @return {nothing}
 */
(function (app, $) {
    "use strict";
    app.modules = {
        setContext: function (o) {
            $.extend(app.modules, o);
        }
    };
}(((undefined !== window.app) ? window.app : {}), jQuery));
/**
 * Initialize the entire app namespace when jquery is ready
 * @return {nothing}
 */
jQuery(document).ready(function () {
    "use strict";
    app.init();
    console.log(app);
});