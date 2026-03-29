import { getSession } from "@/lib/security";
import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { Button } from "@/components/ui/button";
import { BuyForm } from "./components/BuyForm";
import { cookies } from "next/headers";
import { AvailableCredits } from "./components/AvailableCredit";

export interface packsArray {
  uuid: string,
  credit: number,
  price: number,
  message: string | null,
}

export default async function Profile() {
  const session = await getSession();
  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }

  const resCreditPacks = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/credit_packs`, { headers });
  const packsData = await resCreditPacks.json();
  if (!resCreditPacks.ok) {
    throw new Error(packsData.message);
  }

  const resUserData = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${session?.id}`, { headers });
  const userData = await resUserData.json();
  if (!resUserData.ok) {
    throw new Error(userData.message);
  }

  return (
    <main>
      <Container>
        <h1
          className="h1"
          dangerouslySetInnerHTML={{
            __html: language.wallet.title,
          }}
        ></h1>
      </Container>

      <Container className="mt-6">
        <AvailableCredits credits={userData.creditWallet.credit} />

        <Block containerClassName="block-animation mt-4" childClassName="p-4">
            <h2 className="h3 mb-3">{language.wallet.utility.title}</h2>
            <p className="text-gray">{language.wallet.utility.description}</p>
            <ul className="text-gray mt-2">
              <li>{language.wallet.utility.list1}</li>
              <li className="mb-0">{language.wallet.utility.list2}</li>
            </ul>
        </Block>

        <Block containerClassName="block-animation mt-4" childClassName="p-4">
            <h2 className="h3 mb-3">{language.wallet.buy.title}</h2>
            <BuyForm packs={packsData} />
        </Block>
      </Container>
    </main>
  );
}
