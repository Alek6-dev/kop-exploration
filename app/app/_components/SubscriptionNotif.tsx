"use client"

import { Button } from "@/components/ui/button";
import { useEffect } from "react";

const SubscriptionNotif = () => {

    const applicationServerKey = "test";

    // useEffect(() => {
    //     if ('serviceWorker' in navigator) {
    //         navigator.serviceWorker.ready.then(function(registration) {
    //             registration.pushManager.subscribe({
    //                 userVisibleOnly: true,
    //                 applicationServerKey
    //             })
    //             .then(function(subscription) {
    //                 console.log('Subscribed for push:', subscription.endpoint);
    //             })
    //             .catch(function(error) {
    //                 console.log('Subscription failed:', error);
    //             });
    //         });
    //     }
    // }, []);

    // const sendNotif = () => {
    //     console.log("send notif");
    //     new Notification("Coucou !", {
    //         tag: "soManyNotification",
    //     });
    // }

    return(
        <>
            {/* <Button onClick={() => sendNotif() } className="fixed w-full">Send notif</Button> */}
        </>
    )
}

export { SubscriptionNotif };
