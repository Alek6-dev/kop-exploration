// const installEvent = () => {
//     self.addEventListener('install', () => {
//     console.log('service worker installed!!!!');
//     });
// };

// installEvent();

// const activateEvent = () => {
//     self.addEventListener('activate', () => {
//     console.log('service worker activated!!!');
//     });
// };

// activateEvent();


// self.addEventListener('fetch',() => {return});

// const receivePushNotification = (event) => {
//     console.log('receivePushNotification', event);
//     const notification = event.data.json();
//     const title = notification.title || 'Annecy festival';
//     const options = {
//         'data': notification.data || [],
//         'body': notification.body,
//         'icon': notification.icon || '/assets/favicon/android-chrome-512x512.png',
//         'vibrate': [200, 100, 200],
//     };

//     event.waitUntil(
//         self.registration.showNotification(title, options)
//     );
// };

// const openPushNotification = (event) => {
//     const { notification, action } = event;
//     if (action === 'close') {
//         return notification.close();
//     }

//     if (notification.data && notification.data.url) {
//         clients.openWindow(notification.data.url);
//         return notification.close();
//     }
// };

// self.addEventListener("push", receivePushNotification);
// self.addEventListener("notificationclick", openPushNotification);
