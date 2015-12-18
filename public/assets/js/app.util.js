(function (app, $) {
    "use strict";
    app.util = (app.util || {});
    var cache = {};

/**
 * ------------------------------------------------------------
 * Pub/Sub:
 * (https://gist.github.com/cowboy/661855)
 */
    app.util.publish = function () {
        app.registry.trigger.apply(app.registry, arguments);
    };
    app.util.subscribe = function () {
        app.registry.on.apply(app.registry, arguments);
    };
    app.util.unsubscribe = function () {
        app.registry.off.apply(app.registry, arguments);
    };


/**
 * [autocomplete description]
 * @param  {[type]} ele [description]
 * @return {[type]}     [description]
 */

    app.util.lists = {
    };

    function getList(source, def) {
        var key, list;
        key = source.split('/').join('');
        if (app.util.isCached(key, app.cache)) {
            list = app.util.getCache(key, app.cache);
            if (def) {
                def.resolve(list);
            }
            return list;
        }
        $.ajax({
            url: source,
            dataType: 'json'
        }).done(function (ret) {
            list = ret;
            app.util.setCache(key, app.cache, list);
            if (def) {
                def.resolve(list);
            }
            return list;
        });
    }
    function filter(term, source, field) {
        var iter, list, result = [], def = $.Deferred(), user, value;
        if (undefined !== field) {
            field.addClass('thinker');
        }
        list = getList(source, def);
        term = term.toLowerCase();
        for (iter in list) {
            if (list.hasOwnProperty(iter) && undefined !== list[iter]) {
                user = {
                    value: list[iter],
                    name: iter
                };
                value = list[iter].toLowerCase() + iter.toLowerCase();
                if (value.search(term) !== -1) {
                    result.push(user);
                }
            }
        }
        if (undefined !== field) {
            field.removeClass('thinker');
        }
        return result;
    }

    function Multivalue(ele) {
        this.value = [];
        this.body = ele;
        this.button = ele.find('button.multivalue-add-button');
        this.store = ele.find('ul.multivalue-store');
        this.input = ele.find('input.multivalue-lookup');
        this.hidden = ele.find('input.multivalue-value');
        function initEvents(multi) {
            multi.button.unbind('click').on('click', function (event) {
                event.preventDefault();
                var i, l, val, line, list, source, display;
                val = display = multi.input.val();
                source = multi.input.data('source');
                if (undefined !== source) {
                    list = getList(source);
                    for (l in list) {
                        if (list.hasOwnProperty(l)) {
                            if (list[l] === val) {
                                display = l;
                            }
                        }
                    }
                }
                if (val.length === 0) {
                    return;
                }
                for (i in multi.value) {
                    if (multi.value[i] === val) {
                        return;
                    }
                }
                line = '<li class="list-group-item">' +
                    '<button class="multivalue-remove btn-danger pull-right" data-val="' + val + '" type="button">' +
                        '<span class="glyphicon glyphicon-trash"></span>' +
                    '</button>' +
                    display +
                    '</li>';
                multi.value.push(val);
                multi.hidden.val(multi.value.join(','));
                multi.store.append(line);
            });
            multi.body.on('click', 'button.multivalue-remove', function (event) {
                event.preventDefault();
                var index, i = 0, val, clicked = $(event.target);
                if (!clicked.hasClass('multivalue-remove')) {
                    clicked = clicked.parents('button');
                }
                val = clicked.data('val');
                while (undefined === index) {
                    if (multi.value.hasOwnProperty(i)) {
                        if (multi.value[i] === val) {
                            index = i;
                        }
                    } else {
                        index = -1;
                    }
                    i++;
                }
                if (index !== -1) {
                    multi.value.splice(index);
                }
                console.log(multi.value);
                clicked.parents('li').remove();
                multi.hidden.val(multi.value.join(','));
            });
            multi.input.on('typeahead:select', function (event, slug) {
                console.log(event, slug);
            });
        }
        initEvents(this);
    }

    app.util.autocomplete = function (ele) {
        if (undefined !== ele.attr('list')) {
            return;
        }
        var source = ele.data('source'), def = $.Deferred();
        def.then(getList(source, def)).done(function () {
            ele.typeahead(
                {
                    highlight: true,
                    hint: true,
                    name: ele.attr('name'),
                    limit: 10,
                    minLength: 3,
                    source: function (term, sync) {
                        console.log(term);
                        var data = filter(term, source, ele);
                        return sync(data);
                    },
                    matcher: function (item) {
                        return true;
                    },
                    dislayText: function (item) {
                        return item.name;
                    },
                    updater: function (item) {
                        return item.value;
                    }
                }
            ).on('typeahead:select', function (event, sug) {
                console.log('selected', sug);
            }).on('typeahead:render', function (event, sug) {
                console.log('render', sug);
            });
        });
    };

    app.util.initForm = function (form) {
        form.find('input.autocomplete').each(function (e) {
            app.util.autocomplete(this);
        });
    };

/**
 * ------------------------------------------------------------
 * Throttle an action based on how quickly its requested. For example a window resize
 *  event will fire dozens of times a second. This will tell it not to fire until X amount
 *  of time has passed since the last request.
 */
    app.util.debounce = function (func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            }, wait);
            if (immediate && !timeout) {
                func.apply(context, args);
            }
        };
    };

/**
 *  ------------------------------------------------------------
 * Test a cache object for an item, identified by a key
 * @param  {string}  key   identifier to test for
 * @param  {object}  cache object used for caching items
 * @return {Boolean}       True if item is cached, false if not
 */
    app.util.isCached = function (key, cache) {
        if (cache.hasOwnProperty(key)) {
            return true;
        }
        return false;
    };

/**
 *  ------------------------------------------------------------
 * Retrieve an item from a cache object
 * @param  {string} key   identifier to test for
 * @param  {object} cache object used for caching items
 * @return {object}       if item not found, return false
 */
    app.util.getCache = function (key, cache) {
        if (cache.hasOwnProperty(key)) {
            return cache[key];
        }
        return false;
    };

/**
 *  ------------------------------------------------------------
 * Store an item in a cache object
 * @param {string} key   identifier for new item in object
 * @param {object} cache object used for storing
 * @param {object} item  what is to be cached
 * @return {Boolean}    true if item set, false if cache object not exsist
 */
    app.util.setCache = function (key, cache, item) {
        cache[key] = item;
    };

/**
 *  ------------------------------------------------------------
 * universal handler for loading data & content via ajax
 * @param  {object} args    
 * @return {object}        loaded content
 */
    app.util.load = function (args) {
    //0    args.async = false;
    //    args.method = 'post';
        var ret, request;
        args = $.extend({
            url : null,
            async: false,
            dataType: 'html',
            cache: true
        }, args);
        if (undefined !== args.url) {
            if (app.util.isCached(args.url, app.cache && args.cache)) {
                ret = app.util.getCache(args.url, app.cache);
                if (undefined !== args.success) {
                    args.success(ret);
                }
                return ret;
            }
        }
        request = $.ajax(args);
        request.done(function (data) {
            ret = data;
            if (undefined !== args.success) {
                args.success(ret);
            }
        });
        app.util.setCache(args.url, app.cache, ret);
        return ret;
    };

    app.util.ajaxSubmit = function (submit) {
        submit.preventDefault();
        var form, url, data, method;
        form = $(submit.target);
        url = form.attr('action');
        method = (form.attr('method') || 'post');
        data = form.serialize();
        $.ajax({
            url: url,
            data: data,
            method: method
        }).done(function (resp) {
            app.util.sysMsg({
                msg: 'Form successfully submited',
                context: 'success',
                prependTo: form
            });
            form.submitted = true;
        }).error(function (resp) {
            app.util.sysMsg({
                msg: 'Form submission failed',
                context: 'warn',
                prependTo: form
            });
            form.submitted = false;
        }).always(function () {
            app.publish('form', {form: form});
        });
    };

    app.util.ckeditor = function (ele) {
        var name, ck;
        name = ele.attr('name');
        ck = CKEDITOR.instances[name];
        if (ck) {
            CKEDITOR.remove(ck);
        }
        ele.ckeditor();
    };

    app.util.alertUser = function (msg, timeout) {
        var tmp, btn;
        if (typeof msg !== 'string' || msg.length === 0) {
            return;
        }
        tmp = '<div class="alert alert-dismissable alert-warning" role="alert">' +
            '   <button type="button" class="close" data-dismiss="alert">' +
            '       <span aria-hidden="true">&times;</span>' +
            '       <span class="sr-only">Close</span>' +
            '   </button>' +
            '   <span class="alert-message">' + msg + '</span>' +
            '</div>';
        app.content.prepend(tmp);
        tmp = $(tmp);
        tmp.alert();
        if (undefined !== timeout) {
            setTimeout(function () {
                tmp.alert('close');
            }, timeout);
        }
    };

    app.util.ajaxContent = function (link, callback) {
        var args, container = app.body.find('div.content');
        app.util.startThinking(container);
        args = {
            url: link.attr('href'),
            async: false,
            success: function (data) {
                container.html(data);
            }
        };
        app.util.load(args);
        if (undefined !== callback) {
            callback();
        }
    };

    /**
     * create a DOM node for content
     */
    app.util.buildNode = function (args) {

        // set defaults and override with arguments
        args = $.extend({
            target:         null, // $(object) | CSS Selector | 'modal' | 'popover' | null (return new node object)
            appendTo:       app.body, // $(object) | CSS Selector | app.ui.body
            size:           'sm', // sm | md | lg 
            title:          null, // string | null ('Modal' & 'Popover' used as defaults)
            targetElement:  app.body, // $(object) | CSS Selector | app.ui.body
            content:        null, // $(object) | string | null (default UI loading gif)
            cssClass:       null, // string | null (none)
            nid:            null // string | null (none)
        }, args);

        // local variables for node and wrapper if needed (popover, modal)
        var node = $('<div class="node"></div>'),
            wrapper = null;

        // if the requested display target is left out
        if (null === args.target) {

            // generate the DOM element for the popover
            wrapper =  $('<div class="node-wrapper"></div>');

            // inject the node into the wrapper
            wrapper.append(node);

        // if requested display is a modal
        } else if (args.target === 'modal') {

            // generate the DOM element for the modal
            wrapper = $('<div class="modal fade node-wrapper">' +
                        '  <div class="modal-dialog modal-' + args.size + '">' +
                        '    <div class="modal-content">' +
                        '      <div class="modal-header">' +
                        '        <button type="button" class="close" data-dismiss="modal">' +
                        '           <span aria-hidden="true">&times;</span>' +
                        '           <span class="sr-only">Close</span>' +
                        '        </button>' +
                        '        <h4 class="modal-title">' + ((null !== args.title) ? args.title : 'Modal') + '</h4>' +
                        '      </div>' +
                        '      <div class="modal-body"></div>' +
                        '    </div>' +
                        '  </div>' +
                        '</div>');

            // inject the node into the wrapper
            wrapper.find('div.modal-body').append(node);

            // for scope, append the node to whatever is set to appendTo (defauts to app body)
            args.appendTo.append(wrapper);

            // fire off the modal
            wrapper.modal();

            // bind the bootstrap remove event, so we lose the modal completely
            wrapper.on('hidden.bs.modal', function () {
                wrapper.remove();
            });

        // if the requested display is a popover
        } else if (args.target === 'popover') {

            // generate the DOM element for the popover
            wrapper =  $('<div class="popover show popover-' + args.size + ' node-wrapper">' +
                        '   <h3 class="popover-title">' +
                                ((null !== args.title) ? args.title : 'Popover') +
                        '       <button class="btn btn-xs btn-default close-popover pull-right">Close</button>' +
                        '   </h3>' +
                        '   <div class="popover-content"></div>' +
                        '</div>');

            // inject the node into the wrapper
            wrapper.find('div.popover-content').append(node);

            // for scope, append the node to whatever is set to appendTo (defaults to app body)
            args.appendTo.append(wrapper);

            // set the popover's position, relative to the clicked element
            wrapper.position({
                my: 'left center',
                at: 'left top',
                of: args.targetElement
            });

            // mr fancy-pants draggable popover
            wrapper.draggable({
                snap : true,
                containment: "body"
            });

            // bind events to the close button
            wrapper.on('click', 'button.close-popover', function (e) {
                e.preventDefault();
                wrapper.remove();
            });

        // if the requested display target is a CSS selector or dom object
        } else {

            // if the requested display is a CSS selector
            if (typeof args.target === 'string') {
                wrapper = app.ui.body.find(args.target);
            }

            // if the requested display is a DOM object
            if ($.isFunction(args.target.html)) {
                wrapper = args.target;
            }

            // if nothing was found, bail
            if (wrapper.length === 0) {
                return {
                    error: true,
                    msg: 'Could not locate target for node: ' + args.nid
                };
            }

            // if wrapper already marked as completed, bail
            if (wrapper.data('nid') === args.nid) {
                return {
                    error: true
                };
            }

            // append the node to the wrapper
            wrapper.append(node);

        }

        if (null !== args.nid) {
            wrapper.attr('data-nid', args.nid);
        }


        // if no content was included in request, add a loader
        if (null === args.content) {
            args.content = '<span class="sys-loader"></span>';
        }

        // insert content into the node
        node.append(args.content);

        return {
            node: node,
            wrapper: wrapper,
            content: function (element) {
                node.html(element);
            },
            destroy: function () {
                if (node.find('modal')) {
                    wrapper.modal('hide');
                } else {
                    wrapper.remove();
                }
            }
        };
    };

    app.util.node = function (args) {
        var time, node = {
            container: $('<div class="node"></div>'),
            wrapper: null
        };
        args = $.extend({
            target: 'dialog',
            title: '',
            content: '',
            element: null,
            showIn: app.content,
            size: 'lg',
            position: 'left top'
        }, args);
        console.log(args);
        if (args.target === 'dialog') {
            node.ui = $('<div class="modal fade node-wrapper" ' + ((null !== args.id) ? 'data-node="' + args.id + '"' : '') + '>' +
                        '  <div class="modal-dialog modal-' + args.size + '">' +
                        '    <div class="modal-content">' +
                        '      <div class="modal-header">' +
                        '        <button type="button" class="close" data-dismiss="modal">' +
                        '           <span aria-hidden="true">&times;</span>' +
                        '           <span class="sr-only">Close</span>' +
                        '        </button>' +
                        '        <h4 class="modal-title">' + ((null !== args.title) ? args.title : 'Modal') + '</h4>' +
                        '      </div>' +
                        '      <div class="modal-body"></div>' +
                        '    </div>' +
                        '  </div>' +
                        '</div>');
            node.ui.find('.modal-body').append(node.container);
            args.showIn.append(node.ui);
            node.ui.modal('show');
            node.ui.on('hidden.bs.modal', function (event) {
                node.ui.remove();
            });
        } else if (args.target === 'popover') {
            if (!args.element) {
                app.util.alertUser('Something was supposed to happen. But it didn\'t.');
            }
            time = new Date();
            node.time = time.toString();

            node.ui = $('<div class="resizable popover show popover-' + args.size + ' node-wrapper" ' + ((null !== args.id) ? 'data-node="' + args.id + '"' : '') + ' data-time="' + node.time + '">' +
                        '   <h3 class="popover-title">' +
                                ((null !== args.title) ? args.title : 'Popover') +
                        '       <button class="btn btn-xs btn-default close-popover pull-right">Close</button>' +
                        '   </h3>' +
                        '   <div class="popover-content"></div>' +
                        '</div>');
            node.ui.find('div.popover-content').append(node.container);
            args.showIn.append(node.ui);
            node.ui.position({
                my: 'left top',
                at: args.position,
                of: args.element
            });
            node.ui.draggable();
            node.ui.popover('show');
            node.ui.on('click', 'button.close-popover', function (event) {
                node.ui.remove();
                event.stopPropagation();
            });
        } else if (typeof args.target === "string") {
            //args.target is a css selector
            node.ui = app.body.find(args.target);
            node.ui.html(node.container);
        } else {
            node.ui = $(args.target);
            node.ui.html(node.container);
        }
        node.content = function (elem) {
            node.ui.find('div.node').empty();
            node.container.append(elem);
            app.util.initMarkup(node.ui);
        };
        node.content(args.content);
        return node;
    };

    app.util.loadContent = function (args) {
        var content, node;
        args = $.extend({
            url: null,
            target: 'div.content',
            async: true,
            success: null,
            fail: null,
            loader: true,
            cache: false,
            title: '',
            data: {},
            element: null,
            content: null
        }, args);

        if (!args.url && !args.content) {
            app.util.alertUser('Sorry, we failed to load content.');
            return;
        }

        node = app.util.node(args);

        if (args.loader) {
            node.container.empty();
            app.util.startThinking(node.container);

        }
        if (args.content) {
            if (typeof args.content === 'object') {
                content = args.content.html();
            } else if (app.content.find(args.content).length > 0) {
                content = app.content.find(args.content).html();
            } else {
                content = args.content;
            }
            node.content(content);
        } else {
            $.ajax({
                url: args.url,
                async: args.async,
                data: args.data
            }).done(function (resp) {
                node.content(resp);
                app.util.initMarkup(node.ui);
                if (args.success) {
                    args.success(resp);
                }
            }).fail(function (resp) {
                app.util.alertUser('Request Failed. Sorry.');
                if (args.fail) {
                    args.fail(resp);
                }
            });
        }
    };

    app.util.initMarkup = function (node) {
        node.find('textarea').each(function () {
            app.util.ckeditor($(this));
        });
        node.find('input.input-file-list').each(function () {
            var field = $(this);
            app.util.fileField(field.parent('form'), field);
        });
        node.find('table.table-enhanced').each(function (table) {
            table = $(this);
            app.util.enhanceTable(table, table.data());
        });
        node.find('input.autocomplete').each(function () {
            var ele = $(this);
            app.util.autocomplete(ele);
        });
        node.find('.field-multivalue').each(function () {
            var ele = $(this);
            new Multivalue(ele);
        });
    };

    app.util.dialog = function (url, title) {
        var dialog, div, req;
        if (!app.util.isCached('dialog', cache)) {
            div = '<div class="modal" role="dialog" data-keyboard="true">' +
                '<div class="modal-dialog modal-lg" aria-hidden="true">' +
                    '<div class="modal-content">' +
                        '<div class="modal-header">' +
                            '<button type="button" class="close pull-right" data-dismiss="modal">' +
                                '<span class="glyphicon glyphicon-remove"></span>' +
                            '</button>' +
                            '<h4 class="modal-title"></h4>' +
                        '</div>' +
                        '<div class="modal-body"></div>' +
                    '</div>' +
                '</div>' +
                '</div>';
            dialog = $(div);
            app.util.setCache('dialog', cache);
        } else {
            dialog = app.util.getCache('dialog', cache);
        }
        req = {
            url: url,
            success: function (data) {
                dialog.find('.modal-body').html(data);
            }
        };
        app.util.load(req);
        dialog.find('.modal-title').html(title);
        dialog.modal({keyboard: true});
    };

    app.util.enhanceTable = function (table, args) {
        var settings = {
            length: -1,
            paging: true,
            stateSave: false,
            destroy: true
        };
        $.extend(settings, args);
        console.log(settings, args, table);
        table.dataTable(settings);
    };

    app.util.startThinking = function (ele) {
        ele.html('<div class="thinker"></div>');
    };


    /**
     * System message handler
     */
    app.util.sysMsg = function (args) {
        // local options object
        var o = {};
        // override defaults with arguments
        o = $.extend({
            debug : false,
            msg : 'Something happened.',
            context : 'danger',
            display : 'panel',
            ui : '',
            prependTo : 'div.app-primary'
        }, args);
        // Add a context-related heading
        if (o.context === 'danger') {
            o.msg = '<strong>Error:</strong> ' + o.msg;
        }
        // If the desired UI is a panel, create and insert into the DOM
        if (o.display === 'panel') {
            o.ui =  $('<div class="alert alert-dismissable alert-' + o.context + '" role="alert">' +
                    '   <button type="button" class="close" data-dismiss="alert">' +
                    '       <span aria-hidden="true">&times;</span>' +
                    '       <span class="sr-only">Close</span>' +
                    '   </button>' +
                        o.msg +
                    '</div>');
            app.body.find(o.prependTo).prepend(o.ui);
        }
    };

    app.util.confirm = function (args) {
        args = $.extend({
            msg: 'Are you sure?',
            buttons: {
                yes: {
                    text: 'Yes',
                    callback: function () {
                        return true;
                    },
                    css: 'btn btn-primary'
                },
                no: {
                    text: 'No',
                    callback: function () {
                        return false;
                    },
                    css: 'btn btn-danger'
                }
            }
        }, args);
        var i, markup, buttons = '', node;
        for (i in args.buttons) {
            if (args.buttons.hasOwnProperty(i)) {
                buttons = buttons + '<button data-button="' + i + '" class="' + args.buttons[i].css + ' confirm-button">' + args.buttons[i].text + '</button>';
            }
        }
        markup = '<div class="confirm-box"><div class="well">' + args.msg + '</div><div class="btn-group">' + buttons + '</div></div>';
        node = app.util.node({
            title: 'Please Confirm',
            content: markup,
            target: 'dialog'
        });
        node.ui.on('click', 'button.confirm-button', function (event) {
            event.preventDefault();
            var button = $(event.target);
            if (args.buttons.hasOwnProperty(button.data('button'))) {
                button = button.data('button');
                if (undefined !== args.buttons[button].callback) {
                    args.buttons[button].callback(event, node);
                }
            }
        });
    };


        /*
     * create a multi-file upload tool
     * ............................................................
     */
    app.util.fileField = function (form, input, url) {
        var controls = input.siblings('div.form-browse'),
            uniqueId = input.attr("id"),
            upContainer = form.find('#' + uniqueId + '-container')[0],
            upDropElement = form.find('#' + uniqueId + '-dropElement')[0],
            browseBtn = form.find('#' + uniqueId + '-browse'),
            removeBtn = form.find('#' + uniqueId + '-remove-btn'),
            downloadBtn = form.find('#' + uniqueId + '-download-btn'),
            fileList = form.find('#' + uniqueId + '-files'),
            fileHeader = fileList.find('.header'),
            filesBody = fileList.find('.files-body'),
            filesEmpty = form.find('#' + input.attr("id") + '-files-empty'),
            notice = input.siblings('div.form-current-files-changed'),
            fileIdArray = [],
            fileInfoArray = [],
            fileLimit = input.data('file-limit'),
            widget = {},
            uri = (url || '/uploads/do_upload/' + input.attr('name'));

        if (input.attr('disabled') === 'disabled') {
            controls.find('.input-browse').text('Upload Disabled');
            fileList.find('.form-current-file-delete').remove();
            fileList.find('.input-text').attr('disabled', 'disabled');
            return;
        }
        if (input.val() !== '') {
            fileIdArray = input.val().split(',');
        }

        function alertUser() {
            notice.effect('highlight');
        }
        /**
         *  download all files
         */
        function downloadAllFiles(e) {
            var dUrl;
            // // intercept the event
            e.preventDefault();

            // setup dUrl for file download
            dUrl = '/forms/files/download-all?name=Multi-File&ids=[' + fileIdArray + ']';
            window.location.href = dUrl;

            return false;
        }
        function disableSelectFiles() {
            // disable Select Files button
            browseBtn.addClass('active');
            browseBtn.attr('disabled', 'disabled');
        }
        function enableSelectFiles() {
            // enable Select Files button
            browseBtn.removeClass('active');
            browseBtn.removeAttr('disabled');
        }
        function disableDownloadFiles() {
            // disable Download All Files button
            downloadBtn.addClass('active');
            downloadBtn.attr('disabled', 'disabled');
        }

        function enableDownloadFiles() {
            // enable Download All Files button
            downloadBtn.removeClass('active');
            downloadBtn.removeAttr('disabled');
        }
        function fileList_ClickHandler(e) {
            var data;
            // intercept the event
            e.preventDefault();

             // attempt to download file from the file list
            data = app.util.ajax({url: "/forms/files/generate-download?id=" + $(this).data('repos-id'), format: 'json'});
            // test to see if we have successfully loaded the partial, if not bail
            if (data === null || data.success === 'false') {
                app.util.sysMsg({msg: 'Oops! There was an error downloading the file'});
                return;
            }
            document.location.href = data.url;
        }

        function completeFileUpload(info) {
            fileIdArray.push(info.id);
            fileInfoArray.push(JSON.stringify(info));

            var newFile = $('<tr id="repos-id-' + info.id + '"><td id="repos-id-' + info.id + '" class="form-current-file">' +
                '   <div><span>Name: </span><a class="form-current-file-title" href="#" data-repos-id="' + info.id + '">' + info.name + '</a></div>' +
                '   <div><span>Notes: </span><input type="text" id="file-desc-' + info.id + '" name="file-desc-' + info.id + '" class="input input-text"></div>' +
                '   </td><td><input type="checkbox" name="id[]" class="checkbox" data-repos-id="' + info.id + '">' +
                '</td></tr>');

            if (undefined === currentFiles) {
                var currentFiles = {};
            }
            currentFiles[info.id] = info;

            console.log('arr: ', currentFiles);

            fileList.append(newFile.effect('highlight'));
            filesEmpty.find('span.form-current-file-empty').remove().end();
            fileHeader.show();
            removeBtn.show();
            downloadBtn.show();
            notice.show();
            input.val(fileInfoArray.join(','));
        }

        function removeUploadedFile(id) {
            fileIdArray.splice($.inArray(id.toString(), fileIdArray), 1);
            input.val(fileIdArray.join(','));
            // widget.removeFile(id);
            fileList.find('#repos-id-' + id).remove();
            if (filesBody.children().size() === 0) {
                filesEmpty.append('<span class="form-current-file-empty">No files uploaded (drag files here).</span>');
                fileHeader.hide();
                removeBtn.hide();
                downloadBtn.hide();
                notice.hide();
            }
        }

        function maxFilesUploaded() {
            if (fileLimit <= fileList.find('td.form-current-file').size()) {
                app.util.alertUser('This field is limited to ' + fileLimit + ' upload(s).\nPlease remove current files before proceeding.');
                setTimeout(function () {
                    enableSelectFiles();
                }, 100);
                return true;
            }
            return false;
        }

        function newUploader() {
            var Up = plupload.Uploader;

            return new Up({
                runtimes : 'html5,silverlight,flash,html4',
                container: upContainer,
                drop_element: upDropElement,
                file_data_name: input.attr('name'),
                url : uri,
                browse_button: uniqueId + '-browse',
                multi_selection: ((fileLimit === 1) ? false : true),
                flash_swf_url : '/swf/Moxie.swf',
                silverlight_xap_url : '/silverlight/Moxie.xap',
                init : {
                    FilesAdded: function (Up, files) {
                        // upload files automatically
                        Up.start();
                    },
                    QueueChanged: function (Up, files) {
                        disableSelectFiles();
                    },
                    UploadComplete: function (Up, files) {
                        //console.log('UploadComplete');
                        enableSelectFiles();
                    },
                    FilesRemoved: function (Up, files) {
                        //console.log('FilesRemoved');
                    },
                    Error: function (Up, files) {
                        console.log('Error during file upload.', Up);
                    }
                }
            });
        }

        widget = newUploader();

        widget.init();

        widget.bind('FilesAdded', function (up, files) {
            if (maxFilesUploaded()) {
                up.splice();
                return false;
            }
            controls.addClass('form-browse-uploading');
            up.start();
            alertUser();
        });

        widget.bind('FileUploaded', function (up, file, info) {
            completeFileUpload($.parseJSON(info.response));
        });

        widget.bind('UploadComplete', function (up, file, info) {
            controls.removeClass('form-browse-uploading');
        });

        // select files button - show spinner when add files button is clicked
        browseBtn.on('click', function (e) {
            disableSelectFiles();
            // give file dialog a chance to open and then renable button
            setTimeout(function () {
                enableSelectFiles();
            }, 2000);
        });

        // download all files button
        downloadBtn.on('click', function (e) {
            // need to get the list of id's
            disableDownloadFiles();
            downloadAllFiles(e);
            // give file dialog a chance to open and then renable button
            setTimeout(function () {
                enableDownloadFiles();
            }, 2000);
            alertUser();
        });

        // remove button - removes all files selected for removal
        removeBtn.on('click', function (e) {
            // loop through all files checked for removal
            fileList.find('.checkbox:checkbox:checked').each(function () {
                removeUploadedFile($(this).data('repos-id'));
            });
            removeBtn.attr('disabled', 'disabled');
            alertUser();
        });

        fileList
            // toggle the remove button
            .on('change', 'input[type=checkbox]', function (e) {
                // loop through all checkboxes
                if (fileList.find('.checkbox:checkbox:checked').length > 0) {
                    removeBtn.removeAttr('disabled');
                } else {
                    removeBtn.attr('disabled', 'disabled');
                }
            })
            // download individual file
            .on('click', 'a.form-current-file-title', fileList_ClickHandler);
    };

}(((undefined !== window.app) ? window.app : {}), jQuery));