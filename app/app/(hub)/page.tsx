import { getSession } from "@/lib/security";
import React from "react";
import Image from "next/image";
import { HubButton } from "./_components/hubButton";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { redirect } from "next/navigation";
import { CHAMPIONSHIP_LISTING_PAGE, LOGIN_PAGE, MY_PADDOCK_PAGE, SEASON_GAME_PAGE, SHOP_CARS_PAGE, WALLET_PAGE } from "@/constants/routing";
import { cookies } from "next/headers";

export default async function Home() {
  const session = await getSession();

  if (!session) {
    redirect(LOGIN_PAGE);
  }

  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }

  let carImage = "/assets/images/f1/f1@2x.png";

  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${session?.id}`, { headers });
  const userData = await res.json();

  if(res.ok && userData.carCosmetic != null) {
    carImage =  process.env.NEXT_PUBLIC_API_URL+"/"+userData.carCosmetic.image1;
  }

  if (!res.ok) {
    throw new Error(userData.message);
  }

  return (
    <main>
      <Container className="flex flex-col overflow-hidden content-height">
        <a
          href={MY_PADDOCK_PAGE}
          className="w-full zone flex-grow-[2] mb-4 block-animation no-delay"
        >
          <div className="relative pt-6 pl-6">
            <h2
              className="relative h2"
              dangerouslySetInnerHTML={{ __html: language.hub.paddock }}
            ></h2>
            <div className="relative rounded-full bg-primary h-[24px] w-[24px] flex-centering mt-2">
              <Image
                src="/assets/icons/arrow-in-circle.svg"
                alt=""
                quality={100}
                width={7}
                height={10}
              />
            </div>
          </div>
          <div className="absolute hub-f1">
            <picture className="block w-full translate-x-6 -translate-y-14">
              <Image
                src={carImage}
                alt=""
                quality={100}
                width={430}
                height={285}
                className="relative block max-w-none"
                sizes="430px"
              />
            </picture>
          </div>
        </a>

        <div className="grid grid-cols-2 gap-4 hub-cta">
          <HubButton
            url={SEASON_GAME_PAGE}
            title="Saison"
            className="gradient-white-primary delay-200"
            icon="podium"
            iconClassName="svg-primary"
            badge="Nouveauté"
          />
          <HubButton
            url={CHAMPIONSHIP_LISTING_PAGE}
            title={language.hub.championship}
            className="gradient-white-gray delay-200"
            icon="trophy"
            iconClassName="svg-white"
          />
          <HubButton
            url={SHOP_CARS_PAGE}
            title={language.hub.store}
            className="gradient-white-gray delay-200"
            icon="store"
            iconClassName="svg-white"
          />
          <HubButton
            url={WALLET_PAGE}
            title={language.hub.wallet}
            className="gradient-white-primary delay-200"
            icon="wallet"
            iconClassName="svg-primary"
          />
        </div>
      </Container>
    </main>
  );
}
