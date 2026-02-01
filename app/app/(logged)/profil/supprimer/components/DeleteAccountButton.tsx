"use client"

import React from "react";
import language from "@/messages/fr";
import { Button } from "@/components/ui/button";
import { toast } from "@/components/ui/use-toast";
import { PROFILE_CONFIRMATION_DELETE_PAGE } from "@/constants/routing";
import {  useRouter } from "next/navigation";
import { deleteAccount_action } from "@/actions/profile/deleteAccount-action";
import Cookies from "js-cookie";

const DeleteAccountButton = ({uuid} : {uuid: string}) => {

    const router = useRouter();

    async function DeleteAccount (event: React.MouseEvent<HTMLButtonElement, MouseEvent>): Promise<void> {
        event.preventDefault();

        const resDeleteString: string = await deleteAccount_action(uuid);
        const resDelete = JSON.parse(resDeleteString);

        if (resDelete.status === 1) {
            const sessionCookie = Cookies.get("session");
            if (sessionCookie) { Cookies.remove("session") };
            router.push(PROFILE_CONFIRMATION_DELETE_PAGE);
            return;
        }

        if (!resDelete.ok) {
            toast({
                title: resDelete.message,
                variant: "destructive",
            });
        }
        return;
    }

    return (
        <Button variant="destructive" className="mt-4" onClick={DeleteAccount}>{language.profile.delete.button}</Button>
    );
}

export { DeleteAccountButton }
