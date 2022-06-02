(function ($) {
	if (!window.console) window.console = { log: function () { } }
    function TISClient(container, options) {        
        //-----------------------Define-----------------------//
        var opts = {
            autoConnect: true,
            name: "Anonymous",
            generateUserEvent: true,
            generateAuditEvent: false,
            onLoadDataFinished: false,
            host: "mqtt.dms.aodianyun.com",
            sslHost: "mqttdms.aodianyun.com"
        };
        if(options) {
            options = options || {};
            opts.onInitUI=              options.template.onInitUI;
            opts.onLoadHistory=         options.template.onLoadHistory;
            opts.onLoadGroup=           options.template.onLoadGroup;
            opts.onLoadFaces=           options.template.onLoadFaces;
            opts.onReceiveMessage = options.template.onReceiveMessage;
            opts.onPreSendMessage = options.template.onPreSendMessage;
            opts.historyPageSize = options.template.historyPageSize ? options.template.historyPageSize : 10;
            opts.facePageSize = options.template.facePageSize ? options.template.facePageSize : 100;
            opts.groupPageSize = options.template.groupPageSize ? options.template.groupPageSize : 10;
            opts.setTisApp = options.template.setTisApp;
            if (options.template.version && options.template.version >= 2) {
                alert("模版版本太高啦");
            }
        }
        function setOptions(options) {
            options = options || {};
            for (var item in options) {
                opts[item] = options[item];
            }
            opts.useFlash = !window.WebSocket;
        };
        setOptions(options);
        var status = "ok";
        var faceMap = {};
        var groupList = [];
        var groupFaceMap = {};
        var isConnected = false;
        var clientid_ = opts.clientid?opts.clientid:null;
        function errorLog(prefix,error,where,failure) {
            if (typeof error != "string") {
                console.log("[fail] "+ prefix + " :", error)
            }
            if ((typeof failure == "undefined") || failure) {
                if (opts.failure && !failure)
                    opts.failure(error, where);
            }
        }
        function infoLog(prefix,content) {
            console.log("[info] " + prefix + " :", content);
        }
        function GetTisInst() {
            opts.api.getTisInst({
                success: function (data) {
                    opts.topic = data.topic;
                    opts.subkey = data.subkey;
                    if(opts.useFlash){
                        LoadSwfObject();
                    }else{
                        LoadWs();
                    }
                },
                failure: function (error) {
                    errorLog("tis.getTisInst",error,"getTisInst");
                }
            });
        }
        function SendMessage(body, to, extra) {
            if (!body) return;
            if (body.length > 1000) {
                errorLog("tis.SendMessage", "body is too long", "SendMessage");
                return;
            }
            var obj = {
                cmd: "tismsg",
                v: 1,
                from: clientid_,
                name: encodeURI(opts.name ? opts.name : ""),
                to: to ? to : "",
                body: encodeURI(body),
                image: opts.image ? opts.image : "",
                time: parseInt((Date.parse(new Date()) / 1000)),
                extra: encodeURI(extra ? extra : null)
            }
            var content = JSON.stringify(obj);
            infoLog("tis.SendMessage", content);
            var send = true;
            if (opts.onPreSendMessage) send = !!opts.onPreSendMessage(prepareMessage($.parseJSON(content)), container, faceMap);
            if (!send) return;
            opts.api.sendMsg({
                content: content,
                success: function (data) {
                    if (opts.onSendSuccess)
                        opts.onSendSuccess(data);
                },
                failure: function (error) {
                    errorLog("tis.sendMsg",error,"sendMsg");
                }
            });
        };
        function Connect() {
            if (isConnected || status != "ok") return;
            isConnected = true;
            if (opts.useFlash) {
                window.__tis_OnPublish = onMessage;
                window.__tis_EnterFail = function (err) {
                    infoLog("tis.__tis_EnterFail", opts.subkey);
                    try { if (opts.onConnectClose) opts.onConnectClose(); } catch (err) { errorLog("tis.onConnectClose", err, "onConnectClose", false); }
                }
                window.__tis_EnterSuc = function () {
                    if (opts.generateUserEvent) {
                        opts.flashObj.flash_Subscribe("__present__" + opts.topic, 1);
                    }
                    if (opts.generateAuditEvent) {
                        opts.flashObj.flash_Subscribe("__audit__" + opts.topic, 1);
                    }
                    opts.flashObj.flash_Subscribe(opts.topic, 1);
                    try { if (opts.onConnect) opts.onConnect({ topic: opts.topic, clientId: clientid_ }); } catch (err) { errorLog("tis.onConnect", err, "onConnect", false); }
                }
                window.__tis_Offline = function () {
                }
                window.__tis_Reconnecting = function () {
                    try { if (opts.onReconnect) opts.onReconnect(); } catch (err) { errorLog("tis.onReconnect", err, "onReconnect", false); }
                }
                clientid_ = opts.flashObj.flash_Enter("---", opts.subkey, clientid_);
            } else {
                wsconnect();
            }
        };
        function wsconnect() {
            if (status != "ok") return;
            if (!clientid_) {
                clientid_ = "tis-" + Paho.MQTT.NewGuid().replace(new RegExp("\-", "g"), "");
                infoLog("tis.clientid",clientid_);
            } else {
                try { if (opts.onReconnect) opts.onReconnect(); } catch (err) { errorLog("tis.onReconnect",err,"onReconnect",false); }
            }
            var mqttClient_ = new Paho.MQTT.Client(opts.useSSL?opts.sslHost:opts.host, opts.useSSL?Number(8300):Number(8000), clientid_);
            mqttClient_.onConnectionLost = function (responseObject) {
                if (responseObject.errorCode !== 0) {
                    if (mqttClient_ != null) {
                        try {
                            mqttClient_.disconnect();
                        } catch (err) {
                            errorLog("tis.mqttClient_.disconnect",err,"mqttClient_.disconnect",false);
                        }
                        mqttClient_ = null;
                        setTimeout(wsconnect, 100);
                    }
                }
            };
            mqttClient_.onMessageArrived = function (message) {
                onMessage(message.payloadString,message.destinationName)
            }
            mqttClient_.connect({
                timeout: 10,
                userName: "",
                password: opts.subkey,
                keepAliveInterval: 60, 
                cleanSession: false,
                useSSL: !!opts.useSSL,
                onSuccess: function () {
                    if (opts.generateUserEvent) {
                        mqttClient_.subscribe("__present__" + opts.topic, { qos: 1 });
                    }
                    if (opts.generateAuditEvent) {
                        mqttClient_.subscribe("__audit__" + opts.topic, { qos: 1 });
                    }
                    mqttClient_.subscribe(opts.topic, { qos: 1 });
                    try { if (opts.onConnect) opts.onConnect({ topic: opts.topic, clientId: clientid_ }); } catch (err) { errorLog("tis.onConnect",err,"onConnect",false); }
                },
                onFailure: function (err) {
                    try { if (opts.onConnectClose) opts.onConnectClose(); } catch (err) { errorLog("tis.onConnectClose",err,"onConnectClose",false); }
                    setTimeout(wsconnect, 1000)
                }
            });
        }
        function LoadWs() {
            var hasPaho = (typeof Paho != "undefined")
            if (!hasPaho) {
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = false;
                s.src = opts.useSSL ? 'https://cdn.aodianyun.com/dms/ws.js' : 'http://cdn.aodianyun.com/dms/ws.js';
                s.charset = 'UTF-8';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(s);
            }
            var wait = 100
            var timerrr = setInterval(function () {
                hasPaho = (typeof Paho != "undefined")
                if (hasPaho) {
                    clearInterval(timerrr);
                    if (!!opts.onLoadComponent)
                        opts.onLoadComponent();
                    if (opts.autoConnect) {
                        Connect();
                    }
                    return;
                }
                wait--
                if (wait <= 0) {
                    clearInterval(timerrr);
                    if (!hasPaho) {
                        errorLog("tis.LoadWs","load ws.js","LoadWs");
                    }
                }
            }, 10);
        }
        function LoadSwfObject ( ){
            if (typeof swfobject !="undefined") {
                loadFlush();
                return;
            }
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = false;
            s.src = (opts.useSSL ? "https:":"http:") + '//cdn.aodianyun.com/wis/swfobject.js';
            s.charset = 'UTF-8';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(s);
            var wait = 100
            var timerrr = setInterval(function(){                           
                if (typeof swfobject !="undefined") {
                    clearInterval(timerrr);
                    loadFlush();
                    return
                }
                wait--
                if(wait <=0){
                    clearInterval(timerrr);
                    errorLog("tis.LoadSwfObject","load swfobject.js","LoadSwfObject");
                }
            },10);
        }
        function loadFlush(){
            function callback(item){
                if( item.success ){
                    var wait = 1000
                    var timerrr = setInterval(function(){
                        var  flash_obj = swfobject.getObjectById("__tis__");
                        if(flash_obj && flash_obj.flash_Init2){
                            clearInterval(timerrr);
                            flash_obj.flash_Init2(opts.host,1883,"__tis");
                            opts.flashObj = flash_obj;
                            if (opts.autoConnect) {
                                Connect();
                            }
                            return;
                        }
                        wait--
                        if(wait <=0){
                            clearInterval(timerrr);
                            errorLog("tis.loadFlush","tis.flash init","loadFlush");
                        }
                    },10)
                }else{
                    errorLog("tis.loadFlush","tis.flash init","loadFlush");
                }
            }
            if (swfobject.getObjectById("__tis__")) {
                callback({success:true});
                return
            }
            swfobject.embedSWF((opts.useSSL ? "https:":"http:") + "//cdn.aodianyun.com/dms/ROPClient.swf", '__tis__flash__div__', "0", "0", "11.0.0", 
            (opts.useSSL ? "https:":"http:") + "//cdn.aodianyun.com/dms/ROPClient.swf", {id:"__tis__"}, {AllowScriptAccess:"always",wmode:"Transparent"},{id:"__tis__",name:"__tis__",align:"middle"},
            callback);
        }
        function prepareMessage(data) {
            if (data.cmd == "tismsg" && data.from && data.name && data.body) {
                data.name = decodeURIComponent(data.name);
                data.body = decodeURIComponent(data.body);
                data.extra = decodeURIComponent(data.extra);
                data.body = $('<div />').text(data.body).html();
                data.name = $('<div />').text(data.name).html();
                data.body = data.body.replace(new RegExp("\\n", "g"), "<br/>");
                data.body = data.body.replace(new RegExp(" ", "g"), "&nbsp;");
                return data;
            }
            throw "invalid message";
        }
		function htmlRestore(str) {
		  var s = "";
		  if (str.length === 0) {
			return "";
		  }
		  s = str.replace(/&amp;/g, "&");
		  s = s.replace(/&lt;/g, "<");
		  s = s.replace(/&gt;/g, ">");
		  s = s.replace(/&nbsp;/g, " ");
		  s = s.replace(/&#39;/g, "\'");
		  s = s.replace(/&quot;/g, "\"");
		  return s;

		}
        function onMessage(msg,topic) {
            try {
                infoLog("tis.onMessage",topic + ":" + msg);
                var data;
                if (opts.useFlash) {
                    msg = msg.replace(new RegExp("\\\\", "g"), "\\\\\\\\");
                    msg = msg.replace(new RegExp("\n", "g"), "\\\\n");
                    data = opts.flashObj.JSONDecode(msg);
                }else{
                    data = JSON.parse(htmlRestore(msg));
                }
                if (topic == "__sys__") {
                    errorLog("tis.onMessage","killed","");
                    status = "closed";
                    if(opts.useFlash) {
                        opts.flashObj.flash_Leave();
                    } else {
                        mqttClient_.disconnect();
                    }
                    return;
                }
                if (topic == opts.topic) {
                    try {
                        if (!data.cmd || data.cmd != "tismsg") return;
                        //if (data.to && data.to != "" && data.to != clientid_) return;
                        if (data.from && data.name && data.body) {
                            data = prepareMessage(data);
                            opts.onReceiveMessage(data, container, faceMap);
                        }
                    }catch(ex){ errorLog("tis.onReceiveMessage",ex,"onReceiveMessage",false); }
                } else if (topic == "__present__" + opts.topic) {
                    if (opts.updateUser != null && opts.generateUserEvent) {
                        opts.updateUser(data.total, data.clientId, data.state);
                    }
                } else if (topic == "__audit__" + opts.topic) {
                    if (opts.onReceiveAuditNotify != null && opts.generateAuditEvent) {
                        data = prepareMessage(data);
                        opts.onReceiveAuditNotify(data.id, data, faceMap);
                    }
                }
            } catch (err) {
                errorLog("tis.onMessage",err,"onMessage",false);
            }
        }
        function groupClicked() {
            var groupId = parseInt($(this).attr("gid"));
            window.groupFaceMap = groupFaceMap;
            if (opts.onLoadFaces) {
                opts.onLoadFaces(groupFaceMap[groupId], faceMap, container, groupId);
            }
        }
        function loadHistory() {
            if (opts.onLoadHistory) {
                opts.api.getHistory({
                    success: function (data) {
                        infoLog("tis.onLoadHistory",data);
                        opts.onLoadHistory(data, container, faceMap, { prepareMessage: prepareMessage });
                    },
                    failure: function (error) {
                        errorLog("tis.getHistory",error,"getHistory");
                    }
                }, 0, opts.historyPageSize);
            }
        }
        function addFaceMap(groupId, faceKey, faceUrl) {
            groupFaceMap[groupId].push("[" + faceKey + "]");
            faceMap["[" + faceKey + "]"] = faceUrl;
        }
        //------------------------Init------------------------//
        if (opts.onInitUI) opts.onInitUI(container, {name:opts.name,image:opts.image});
        if (opts.useFlash) {
            if (!document.getElementById("tis_flash_support")) {
                $(container).append("<div id='tis_flash_support'></div>");
            }
            $("#tis_flash_support").height(0).width(0);
            $("#tis_flash_support").append('<div id="__tis__flash__div__"></div>');
        }
        GetTisInst();
        opts.api.getFaceGroups({
            success: function (groups) {
                infoLog("tis.onLoadGroup",groups);
                var finished = 0;
                groupList = groups.list;
                for (var grpIdx = 0; grpIdx < groupList.length; grpIdx++) {
                    grp = groupList[grpIdx];
                    if (grp.package > 0) {
                        var baseUrl = grp.icon.split('/')[2];
                        opts.api.getPackage({
                            pkgId: grp.package,
                            userId:grp.userId,
                            groupId:grp.id,
                            success: function (pkg, opt) {
                                pkg = pkg.data;
                                infoLog("tis.getPackage",pkg);
                                groupFaceMap[opt.groupId] = [];
                                var packageUrl = (opts.useSSL ? "https:":"http:") + "//" + baseUrl + "/mfs/tis/" + pkg.path + "/";
                                // if (pkg.baseUrl) {
                                //     packageUrl = pkg.baseUrl;
                                // }
                                for (var i = 1; i <= pkg.num; i++) {
                                    addFaceMap(opt.groupId, "#/" + pkg.path + "/" + i, packageUrl + i + "." + pkg.subfix);
                                }
                                finished++;
                                if (finished >= groupList.length) {
                                    loadHistory();
                                    if (opts.onLoadDataFinished) {
                                        opts.onLoadDataFinished(faceMap);
                                    }
                                }
                            },
                            failure: function (error,opt) {
                                errorLog("tis.getPackage(" + opt.pkgId + ")",error,"getPackage");
                            }
                        });
                    } else {
                        opts.api.getFaces({
                            groupId: grp.id,
                            success: function (faces,opt) {
                                infoLog("tis.getFaces", faces);
                                groupFaceMap[opt.groupId] = [];
                                for (var index = 0; index<faces.list.length; ++index) {
                                    var face = faces.list[index];
                                    if (opts.useSSL && face.url) {
                                        face.url = face.url.replace("http://","https://")
                                    }
                                    addFaceMap(opt.groupId, face.text, face.url);
                                }
                                finished++;
                                if (finished >= groupList.length) {
                                    loadHistory();
                                    if (opts.onLoadDataFinished) {
                                        opts.onLoadDataFinished(faceMap);
                                    }
                                }
                            },
                            failure: function (error,opt) {
                                errorLog("tis.getFaces(" + opt.groupId + ")",error,"getFaces");
                            }
                        }, 0, opts.facePageSize);
                    }
                }
                if (opts.onLoadGroup) {
                    opts.onLoadGroup(groupList,container,groupClicked);
                }
                if (groupList.length <= 0) {
                    loadHistory();
                    if (opts.onLoadDataFinished) {
                        opts.onLoadDataFinished(faceMap);
                    }
                }
            },
            failure: function (error) {
                errorLog("tis.onLoadGroup",error,"getFaceGroups");
            }
        }, 0, opts.groupPageSize);
        var tisApp = {
            //Connect: Connect,
            SendMessage: SendMessage,
            SetName: function (name) {
                opts.name = name;
            },
            prepareMessage: prepareMessage,
            getClientId: function () {
                return clientid_;
            }
        }
        if (opts.setTisApp) opts.setTisApp(tisApp);
        return tisApp;
    }
    window.TIS = TISClient;
})(jQuery || $);