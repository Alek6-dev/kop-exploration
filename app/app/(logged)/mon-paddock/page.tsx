import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { ItemEquipped } from "./components/ItemEquipped";
import Image from "next/image";
import { redirect } from "next/navigation";
import { NOTFOUND_PAGE } from "@/constants/routing";
import { cookies } from "next/headers";
import { getSession } from "@/lib/security";
import { Paddock } from "@/components/custom/paddock";

export default async function MyPaddock() {
    const session = await getSession();
    const token = cookies().get("session")?.value;
    const headers = {
        Authorization: `${"Bearer " + token}`,
        "Content-Type": "application/json",
    }

    try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${session?.id}`, { headers });
        const userData = await res.json();

        if (!res.ok) {
            throw new Error(userData.message);
        }

        //console.log(userData);

        return (
            <main>
                <Container>
                    <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: language.mypaddock.title,
                    }}
                    ></h1>
                </Container>

                <div className="mt-6 flex flex-col paddock-content-height">
                    <Paddock car={userData.carCosmetic ? userData.carCosmetic.image1 : ""} helmet={userData.helmetCosmetic ? userData.helmetCosmetic.image2 : ""} carColor={userData.carCosmetic ? userData.carCosmetic.color : "#00FFFF"} />
                    <div className="grid grid-cols-2 mt-auto mb-4 paddock-items">
                        <ItemEquipped image={userData.carCosmetic ? userData.carCosmetic.image2 : ""} name={userData.carCosmetic ? userData.carCosmetic.name : ""} type="car"/>
                        <ItemEquipped image={userData.helmetCosmetic ? userData.helmetCosmetic.image1 : ""} name={userData.helmetCosmetic ? userData.helmetCosmetic.name : ""} type="helmet" />
                    </div>
                </div>
            </main>
        )
    } catch (e: any) {
        redirect(NOTFOUND_PAGE);
    }
}
