localStorage['refresh'] = 5;
window.test = null;
var lang = chrome.i18n.getMessage("@@ui_locale");

if (lang == 'ru')
    localStorage['lang'] = "ru";
else
    localStorage['lang'] = "en";

localStorage['service_url'] = "http://" + localStorage['service'] + "/service/data.php";
var time_offset = new Date().getTimezoneOffset();

function restore_options()
{
    document.getElementById("_save").value = chrome.i18n.getMessage("save");
    document.getElementById("_exit").value = chrome.i18n.getMessage("exit");
    document.title = chrome.i18n.getMessage("options");
    document.getElementById("extension").value = localStorage["txtPrivateIdentity"] ? localStorage["txtPrivateIdentity"] : '';
    document.getElementById("txtPrivateIdentity").value = localStorage["txtDisplayName"] ? localStorage["txtDisplayName"] : '';
    document.getElementById("password").value = localStorage["txtPassword"] ? localStorage["txtPassword"] : '';
    document.getElementById("service").value = localStorage["txtRealm"] ? localStorage["txtRealm"] : '';
    //  document.getElementById("openform").checked = localStorage["openform"]=='true'?true:false;
    document.getElementById("click2call").checked = localStorage["click2call"] == 'true' ? true : false;
    window.test = 6; console.log('aga');
}
function save_options1()
{
    localStorage['org.doubango.identity.display_name'] = document.getElementById("txtPrivateIdentity").value;
}
function restore_options1()
{
    document.getElementById("txtDisplayName").value = localStorage["txtPrivateIdentity"] ? localStorage["txtPrivateIdentity"] : '';
}
function save_options()
{

    localStorage["password"] = document.getElementById("txtPassword").value;
    localStorage['service'] = document.getElementById("txtRealm").value;
    localStorage["extension"] = document.getElementById("txtPrivateIdentity").value;
    localStorage["click2call"] = document.getElementById("click2call").checked;
//  localStorage["openform"]  = document.getElementById("openform").checked;
    localStorage['service_url'] = "http://" + localStorage['service'] + "/service/data.php";
    get_session_id(function(msg) {
        var status = document.getElementById("status");
        status.innerHTML = msg;
        setTimeout(function() {
            status.innerHTML = "";
            chrome.extension.sendRequest({
                'do': "update"
            });
        }, 1750);
    });
}

function main()
{
    if (!localStorage['session_id'])
        return;
    var req = new XMLHttpRequest();
    req.onload = function() {
        var doc = req.responseText;
        if (doc) {
            var text = '';
            var json = JSON.parse(doc);
            //console.log(localStorage['session_id']+":"+doc);
            if (json.success/* && json.status='online'*/) {
                var status = json['status'];
                var incoming_call = json['incoming_call'];

                var cur_state = localStorage['status'];
                if (status && status != cur_state)
                {
                    text = chrome.i18n.getMessage("status") + ' : ' + status;
                    localStorage['status'] = status;
                    init();
                }

                /*                for (var e in status)
                 if (status[e][0] == localStorage['extension']){
                 var cur_state = localStorage['extension_'+localStorage['extension']];
                 if (status[e][1]!=cur_state)
                 {
                 text = chrome.i18n.getMessage("status")+' : ' + status[e][1];
                 localStorage['extension_'+localStorage['extension']] = status[e][1];
                 init();
                 }
                 }
                 */
                if (incoming_call && incoming_call['number']) {
                    var caller = incoming_call.number;
                    var line, called, time;
                    if (incoming_call['line'])
                        line = incoming_call.line;
                    if (incoming_call['called'])
                        called = incoming_call.called;
                    if (incoming_call['time'])
                        time = incoming_call.time;
                    var call_from_msg = chrome.i18n.getMessage("call_from") + ' ' + incoming_call.number;
                    text += ' ' + call_from_msg;
                  /*  var bitrix_msg = chrome.i18n.getMessage("bitrix_message");
                    bitrix_msg = bitrix_msg.replace('{caller}', caller);
                    bitrix_msg = bitrix_msg.replace('{line}', line ? line : '');
                    bitrix_msg = bitrix_msg.replace('{called}', called ? called : '');
                    bitrix_msg = bitrix_msg.replace('{date}', time);*/
                    //"message": "└сюэхэЄ: {caller}\\n┬їюф ∙р  ышэш : {line}\\n╩юьє: {called} \\n─рЄр: {date}\\n╥шя: ┬їюф ∙шщ"
                   /* if (localStorage["openform"] == 'true')
                        chrome.tabs.create({
                            url: bitrix_url
                        }, function(tab) {
                            chrome.tabs.executeScript(tab.id, {
                                code: "document.getElementById('SOURCE_ID').value=" + bitrix_SOURCE_ID
                                        + ";document.getElementById('OWNER_USER_ID').value=" + caller
                                        + ";document.forms[0]['TITLE'].value ='" + call_from_msg + "'"
                                        + ";document.getElementById('MESSAGE').innerHTML='" + bitrix_msg + "'"
                            });
                        });*/
                }
            }
            else
            {
                localStorage.removeItem('session_id');
                text = json.message;
                init();
            }
            if (text)
                showNotification(localStorage['extension'], text);
            //log(author, text, link);

        }
        ;
    };
    req.open("GET", localStorage['service_url'] + "?action=get_state&extension='" + localStorage['extension'] + "'&time_offset=" + time_offset + "&period=" + localStorage['refresh'] + "&" + localStorage['session_name'] + '=' + localStorage['session_id'], true);
    req.send(null);


}

function substr(str, startStr, endStr)
{
    start = str.indexOf(startStr) + startStr.length;
    return str.substring(start, str.indexOf(endStr, start));
}

function showNotification(title, text)
{
    var notification = webkitNotifications.createNotification(
            '',
            title,
            text
            );
    notification.show();
    window.setTimeout(function() {
        notification.cancel();
    }, 5000);
}
var interval;

function init() {
    console.log("session_id :" + localStorage['session_id']);
    if (!localStorage['session_id']/* || localStorage['extension_'+localStorage['extension']]=='offline'*/)
    {

        if (interval != "")
            window.clearInterval(interval);
        interval = "";
        chrome.browserAction.setIcon({
            path: "images/icon_not_logged_in.png"
        });
        return;
    } else
        chrome.browserAction.setIcon({
            path: "images/icon_logged_in.png"
        });
    window.clearInterval(interval);
    interval = window.setInterval(function() {
        //refresh_bitrix();
        main();
    }, localStorage['refresh'] * 1000);
//    console.log(localStorage['refresh'] * 1000);
}
var time = new Date();
function refresh_bitrix() {
   /* if (!localStorage["openform"])
        return;
    if ((new Date() - time) < bitrix_expire_time)
        return;
    //console.log (new Date()-time +':'+ bitrix_expire_time);
    time = new Date();
    var req = new XMLHttpRequest();
    req.open("GET", bitrix_base + '/index.php?dc=' + new Date().getTime(), true);*/
    //req.setRequestHeader('Accept', "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");	
   // req.send(null);
}

document.addEventListener('DOMContentLoaded', function() {
    chrome.extension.onRequest.addListener(
            function(request, sender, sendResponse) {
                if (request.method == "getLocalStorage")
                    sendResponse({
                        data: localStorage[request.key]
                    });
                if (request.method == "getLocalMsg")
                    sendResponse({
                        data: chrome.i18n.getMessage(request.key)
                    });
                if (sender.tab.url == chrome.extension.getURL("options.html") && request.do == "update")
                {
                    get_session_id(init);
                    //console.log('Update refresh time from options !');
                }
            }
    );
    init();
});


window.onload = function() {
window.test = null; 
}