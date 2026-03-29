import { getSession } from "@/lib/security";
import React from "react";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { NOTFOUND_PAGE } from "@/constants/routing";
import { ShopGrid } from "../components/ShopGrid";

export default async function ShopHelmets() {
    const session = await getSession();
    const token = cookies().get("session")?.value;
    const headers = {
        Authorization: `${"Bearer " + token}`,
        "Content-Type": "application/json",
    }

    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/cosmetics?type=3`, { headers });
    const shopData = await res.json();

    if (!res.ok) {
        redirect(NOTFOUND_PAGE);
    }

    const resUserData = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${session?.id}`, { headers });
    const userData = await resUserData.json();
    if (!resUserData.ok) {
        throw new Error(userData.message);
    }

    return(
        <ShopGrid shopData={shopData} type="helmet" credits={userData.creditWallet.credit} />
    )
}
