// Websocket library
(function (app, $) {
    "use strict";

    var local = {
        token: ''
    };

    function emit(args) {
        args.token = local.token;
        local.socket.send(JSON.stringify(args));
    }

    function onMessage(event) {
        var fn, args = JSON.parse(event.data),
            action = 'on' + args.action;
        fn = window.app.modules.socket[action];
        if (typeof fn === 'function') {
            fn(args);
            local.reconnecting = false;
        }
    }

    function onError(event) {
        if (local.socket && !local.reconnecting) {
            app.util.sysMsg({msg: 'Realtime server errored.'});
        }
    }

    function killWhosOnline() {
        app.body.find('li#nav-online').remove();
    }


    function onClose(event) {
        if (local.socket && !local.reconnecting) {
            local.reconnecting = true;
            app.util.sysMsg({msg: 'Realtime server is offline. Some actions may not be available. Error code:' + event.code});
            killWhosOnline();
            if (local.options.autoReconnect) {
                reconnect();
            }
        }
    }

    function initSocket() {
        local = $.extend(app.cache.websocket, local);
        local.socket = new WebSocket(local.uri);
        local.socket.onmessage = onMessage;
        local.socket.onclose = onClose;
        local.socket.onerror = onError;
        local.socket.onopen = function (event) {
            console.log(event);
            local.reconnecting = false;
            app.modules.socket.subscribe(local.topicPrefix + '/chat/broadcast', app.modules.socket.chatReceived);
            app.modules.socket.subscribe(local.topicPrefix + '/chat/' + app.cache.user.username, app.modules.socket.chatReceived);
            app.modules.socket.subscribe(local.topicPrefix + '/chat/users', app.modules.socket.listUsers);
        };
    }

    function reconnect() {
        if (local.reconnecting === true && local.reconnectCount < local.maxReconnect) {
            console.log('reconnecting....');
            initSocket();
            setTimeout(reconnect, local.timeout);
            local.reconnectCount = local.reconnectCount + 1;
        } else {
            console.log('stopped autoReconnect');
        }
    }

    function shadow(user) {
        //to be used later?
        return false;
    }

    function initCache() {
        if (undefined !== app.ui) {
            local.body = app.body.find('div.ui-body');
        } else {
            local.body = $('div.ui-body');
        }
        local.mute = false;
        local.reconnecting = false;
        local.options = {
            keepAlive: true,
            autoReconnect: true,
            fallback: false
        };
        local.reconnectCount = 0;
        local.maxReconnect = 2;
        local.timeout = 5000;
    }

    function sendMessage(event) {
        event.preventDefault();
        var to = local.chatContainer.wrapper.find('#chatRecipients').val(),
            msg = {
                action: 'publish',
                msg: local.chatContainer.wrapper.find('#chatMessage').val(),
                topic: local.topicPrefix + '/chat/' + to,
                sender: app.cache.user.fullName,
                to: to
            },
            now;
        emit(msg);
        if (msg.to !== 'broadcast') {
            now = new Date();
            msg.time = now.toLocaleTimeString();
            app.modules.socket.chatReceived({}, msg);
        }
        local.chatContainer.wrapper.find('#chatMessage').val('');
    }

    function initChat() {
        var chatOps = {
            target: 'popover',
            size: 'lg',
            title: 'Workflow Chat',
            targetElement: app.body.find('#nav-online')
        }, node, chatContainer, content;
        if (undefined !== local.chatContainer) {
            node = local.chatContainer.node;
            local.chatContainer = app.util.buildNode(chatOps);
            local.chatContainer.content(node);
            app.body.find('span.chat-unread-badge').text('');
        } else {
            chatContainer = app.util.buildNode(chatOps);
            local.chatContainer = chatContainer;
            content = '<div id="messageContainer" class="well" style="max-height:150px;overflow:auto;">' +
                '   <ul id="chatMessageContainer" class="list-unstyled"></ul>' +
                '</div>' +
                '<div>' +
                '   <form class="form form-inline" role="form">' +
                '       <div class="input-group">' +
                '           <span class="input-group-addon">' +
                '              <select id="chatRecipients" role="menu"/>' +
                '           </span>' +
                '           <input type="text" class="form-control" id="chatMessage"/>' +
                '           <span class="input-group-btn"><button id="chatSend" class="btn btn-pimary">Send</button></span>' +
                '       </div>' +
                '   </form>' +
                '</div>';
            chatContainer.content(content);
            app.modules.socket.subscribe(local.topicPrefix + '/chat/broadcast', app.modules.socket.chatReceived);
            app.modules.socket.subscribe(local.topicPrefix + '/chat/' + app.cache.user.username, app.modules.socket.chatReceived);
            app.modules.socket.subscribe(local.topicPrefix + '/chat/users', app.modules.socket.listUsers);
            emit({action: 'session'});
        }
        local.chatContainer.wrapper.draggable().resizable();
        local.chatContainer.wrapper
            .on('click', '#chatSend', sendMessage)
            .on('submit', 'form', sendMessage);
    }

    function initWhosOnline() {
        if (app.body.find('#nav-online').length > 0 || app.body.find('#footer-menu').length === 0) {
            return;
        }
        var markup, ui;
        markup = '<li id="nav-online" class="dropdown">' +
            '   <a href="#" data-toggle="dropdown" class="drowdown-toggle">' +
            '       Online <span class="badge chat-unread-badge"></span><span class="caret"></span>' +
            '   </a>' +
            '   <ul class="dropdown-menu clearfix" role="menu"/>' +
            '</li>';
        ui = $(markup);
        app.body.find('#footer-menu').append(ui);
        app.body.on('click', 'a.chat-user', function (event) {
            event.preventDefault();
            initChat();
        });
        app.modules.socket.subscribe(local.topicPrefix + '/chat/users', app.modules.socket.onlineUsers);
        initChat();
        local.chatContainer.wrapper.find('button.close-popover').click();
    }

    app.modules = (app.modules || {});
    app.modules.socket = {
        init: function (ui, args) {
            if (undefined !== app.cache.socket) {
                app.socket = app.cache.socket;
                return;
            }
            initCache();
            initSocket();
        },
        publish: function (args) {
            if (undefined === args.topic) {
                return false;
            }
            args.action = 'publish';
            local.socket.send(args);
        },
        subscribe: function (topic, callback) {
            if (undefined === topic) {
                return false;
            }
            app.subscribe(topic, callback);
            var sub = {
                topic: topic,
                action: 'subscribe'
            };
            emit(sub);
        },
        unsubscribe: function (topic) {
            app.unsubscribe(topic);
            var msg = {
                topic: topic,
                action: 'unsubscribe'
            };
            emit(msg);
        },
        onconnect: function (args) {
            var token = args.response.token;
            local.token = token;
            args.action = 'register';
            args.site = app.cache.site;
            args.app = app.cache.user.app;
            args.username = app.cache.user.username;
            args.displayName = app.cache.user.fullName;
            args.prefix = local.topicPrefix;
            emit(args);
        },
        onregister: function (args) {
            initWhosOnline();
        },
        onpublish: function (args) {
            app.publish(args.topic, args);
        },
        chatReceived: function (event, args) {
            var unreadCount, badge, msg = args.msg,
                sender = args.sender,
                to = args.to,
                display = '<small>' + args.time + '</small> <span class="text-primary">' + sender + '</span>: ';
            if (to === 'broadcast') {
                display = display + '<small class="text-muted">Broadcast</small> ';
            }
            display = display + msg;
            local.chatContainer.wrapper.find('#chatMessageContainer').prepend($('<li class="panel panel-default">').html(display));
            if (local.body.find('#chatMessageContainer').length === 0 && args.to !== 'broadcast') {
                badge = app.body.find('span.chat-unread-badge').first();
                unreadCount = badge.text();
                if ('' === unreadCount) {
                    unreadCount = 0;
                }
                unreadCount = unreadCount + 1;
                badge.text(unreadCount);
            }
        },
        listUsers: function (event, args) {
            var i, select = local.chatContainer.wrapper.find('#chatRecipients');
            local.chatContainer.wrapper.find('#chatRecipients').empty();
            select.append($('<option/>').text('All').val('broadcast'));
            for (i in args.users) {
                if (args.users.hasOwnProperty(i)) {
                    select.append($('<option/>').text(args.users[i]).val(i));
                }
            }
        },
        onlineUsers: function (event, args) {
            var i, uEntry, select = app.body.find('#nav-online ul');
            select.empty();
            for (i in args.users) {
                if (args.users.hasOwnProperty(i)) {
                    uEntry = '<li class="clearfix" style="float:none;">' +
                        '   <a class="chat-user btn btn-defualt btn-xs pull-right" href="#">' + args.users[i] + '</a>' +
                        '</li>';
                    select.append(uEntry);
                }
            }
        },
        shadowCleint: function (event, args) {
            app.util.confirm({
                msg: args.user + ' would like to view your session',
                buttons: ['Allow', 'No, thanks']
            }).done(function (result) {
                var button = $(event.target);
                if (button.text() === 'Allow') {
                    app.body
                        .on('hover', sendShadowEvent)
                        .on('focus', sendShadowEvent)
                        .on('click', sendShadowEvent)
                        .on('change', sendShadowEvent)
                        .end();
                }
            });
        },
        initOnLoad: true
    };
}(((undefined !== window.app) ? window.app : {}), jQuery));