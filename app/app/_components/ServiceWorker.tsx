"use client"

import { Button } from "@/components/ui/button";
import { useEffect } from "react";

const ServiceWorker = () => {

    const applicationServerKey = "test";

    // useEffect(() => {
    //     if ('serviceWorker' in navigator) {
    //         navigator.serviceWorker
    //         .register('/service-worker.js', { scope: '/' })
    //         .then((registration) => {
    //             console.log(
    //                 'Service worker registered successfully. Scope:',
    //                 registration.scope
    //             );
    //         })
    //         .catch((error) => {
    //             console.error('Service worker registration failed:', error);
    //         });


    //         // Check if the navigator manage permission promise for notification
    //         const checkNotificationPromise = () => {
    //             try {
    //               Notification.requestPermission().then();
    //             } catch (e) {
    //               return false;
    //             }
    //             return true;
    //           }

    //         const handlePermission = (permission: any) => {
    //             // On affiche ou non le bouton en fonction de la réponse
    //             if (
    //               Notification.permission === "denied" ||
    //               Notification.permission === "default"
    //             ) {
    //                 console.log('Unable to get permission to notify.');
    //                 // TODO envoyer à l'api l'autorisation ou pas
    //             } else {
    //                 console.log('Notification permission granted.');
    //                 // TODO envoyer à l'api l'autorisation ou pas
    //                 const notification = new Notification("Liste de trucs à faire", {
    //                     body: "pouet",
    //                     icon: "https://cdn-icons-png.flaticon.com/256/831/831327.png",
    //                 });
    //             }
    //         }

    //         // If the navigator manage promise for notification (ie: Chrome), we use requestPermission with a promise, else (ie: Safari) we use requestPermission with a callback (old way
    //         if (!("Notification" in window)) {
    //             console.log("Ce navigateur ne prend pas en charge les notifications.");
    //         } else {
    //             if (checkNotificationPromise()) {
    //                 Notification.requestPermission().then((permission) => {
    //                     handlePermission(permission);
    //                 });
    //             } else {
    //                 Notification.requestPermission(function (permission) {
    //                     handlePermission(permission);
    //                 });
    //             }
    //         }
    //     }
    // }, []);

    // self.addEventListener('push', function(event) {
    //     const data = {
    //         title: 'New Notification',
    //         message: 'This is a notification.',
    //     }
    //     const title = data.title;
    //     const body = data.message;
    //     const icon = 'https://aroundsketch.github.io/Apple-App-Icons/App%20Icon/Apple/AppStore/@SVG.svg';
    //     const notificationOptions = {
    //         body: body,
    //         tag: 'simple-push-notification-example',
    //         icon: icon
    //     };
    //     console.log("push event");

    //     // return self.Notification.requestPermission().then((permission) => {
    //     //   if (permission === 'granted') {
    //     //     return new self.Notification(title, notificationOptions);
    //     //   }
    //     // });
    // });

    return(
        <></>
    )
}

export { ServiceWorker };
