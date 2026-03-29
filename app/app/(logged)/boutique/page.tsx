import { getSession } from "@/lib/security";
import React from "react";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { NOTFOUND_PAGE } from "@/constants/routing";
import { ShopGrid } from "./components/ShopGrid";

export interface shopItemArray {
    uuid: string,
    name: string,
    description: string,
    price: number,
    type: number,
    color: string,
    image1: string,
    image2: string,
    isSelected: boolean,
    isPossessed: boolean,
  }

export default async function ShopCars() {
    const session = await getSession();
    const token = cookies().get("session")?.value;
    const headers = {
        Authorization: `${"Bearer " + token}`,
        "Content-Type": "application/json",
    }

    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/cosmetics?type=1`, { headers });
    const shopData = await res.json();

    if (!res.ok) {
        redirect(NOTFOUND_PAGE);
    }

    const resUserData = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${session?.id}`, { headers });
    const userData = await resUserData.json();
    if (!resUserData.ok) {
      throw new Error(userData.message);
    }


    //console.log("shop data : ", shopData);

    return(
        <ShopGrid shopData={shopData} type="car" credits={userData.creditWallet.credit} />
    )
}
