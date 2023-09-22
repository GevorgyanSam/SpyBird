// ---- -- - ------ --- ---- -------------
// This Is A Method For Push Notifications
// ---- -- - ------ --- ---- -------------

function notify(title, body) {
    $("#push-notification h3").text(title);
    $("#push-notification h4").text(body);
    let notification = $("#push-notification .notification");
    notification.css({
        display: 'flex',
    })
    setTimeout(() => {
        notification.css({
            display: 'none',
        })
    }, 7000);
}

export default notify;
