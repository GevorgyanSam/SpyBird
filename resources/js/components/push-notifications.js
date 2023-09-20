// ---- -- - ------ --- ---- -------------
// This Is A Method For Push Notifications
// ---- -- - ------ --- ---- -------------

function notify(
    title,
    body,
    action = false,
    tag = false,
    icon = "../assets/icon.png"
) {
    if (Notification.permission != "granted") {
        Notification.requestPermission();
    } else {
        const notification = new Notification(title, {
            body: body,
            icon: icon,
            badge: "../assets/icon.png",
            tag: tag,
            requireInteraction: false,
        });

        if (action) {
            notification.onclick = () => {
                open(action);
            };
        }
    }
}

export default notify;
