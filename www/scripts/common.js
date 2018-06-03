// the "data" parameter is the JSON data returned from Notification.send()
// it's expected to have a "recipients" array with specific properties
function ShowToastFromNotificationSend(data) {
    for (var i = 0; i < data.recipients.length; i++) {
        var name = data.recipients[i]["Username"];
        var address = data.recipients[i]["TextAddress"];

        $.toast({ 
            text: '<u>Text to ' + name + ' (' + address + ')</u><br>' + data.body,
            allowToastClose: true,
            hideAfter: false
        });    
    }
}
