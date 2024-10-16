import './bootstrap';

import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your_pusher_app_key',
    cluster: 'your_pusher_app_cluster',
    encrypted: true,
});

window.Echo.channel('admin-notifications')
    .listen('NewUserRegistered', (event) => {
        // Handle the notification, e.g., display it in a toast
        console.log(event.message);
        // You can also display a notification on the UI
        alert(event.message);
    });
