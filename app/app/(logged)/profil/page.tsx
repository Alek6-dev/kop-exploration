import { getSession } from "@/lib/security";
import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { LogoutButton } from "@/app/(guest)/_components/LogoutButton";
import { Stats, StatsArray } from "@/components/custom/stats";
import { cookies } from "next/headers";
import { ABOUT_PAGE, NOTIFICATIONS_PAGE, PROFILE_DELETE_PAGE, PROFILE_EDIT_PAGE } from "@/constants/routing";
import { A } from "@/components/custom/link";
import { Separator } from "@/components/custom/separator";
import { LinkBlock } from "@/components/custom/linkBlock";

export default async function Profile() {
  const session = await getSession();
  const uuid = session?.id;
  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }

  const resStats = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/statistics/user/${uuid}`, { headers });
  const stats = await resStats.json();

  const resNotifications = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/notifications`, {
    headers,
    cache: "no-store",
  });
  const notifications = resNotifications.ok ? await resNotifications.json() : [];
  const hasUnreadNotifications = Array.isArray(notifications) && notifications.some((n: { isRead: boolean }) => !n.isRead);

  //console.log("user stats : ", stats);

  const statsData: Array<StatsArray> = [
    {
      label: language.stats.championships_won,
      value: stats.countChampionshipsWon,
      maxValue: stats.countChampionships,
    },
    {
      label: language.stats.strategies_won,
      value: stats.countStrategiesWon,
      maxValue: stats.countStrategies,
    },
    {
      label: language.stats.duels_won,
      value: stats.countDuelsWon,
      maxValue: stats.countDuels
    },
    {
      label: language.stats.cosmetics_possessed,
      value: stats.countCosmeticsPossessed,
      maxValue: null,
    },
  ]

  return (
    <main>
      	<Container>
			<h1
				className="h1"
				dangerouslySetInnerHTML={{
					__html: language.profile.myprofile.title,
				}}
			></h1>
      	</Container>

      <Container className="mt-6">
        <Block containerClassName="block-animation mb-4" childClassName="grid grid-cols-2">

        {!resStats.ok ?
          <span className="p-4 col-span-2 text-red">Les statistiques n’ont pas pu être chargées.</span>
        :
          statsData.map((stat: StatsArray, index: number) => (
            <Stats key={index} label={stat.label} value={stat.value} maxValue={stat.maxValue} />
          ))
        }
        </Block>

        <LinkBlock title="Notifications" url={NOTIFICATIONS_PAGE} description="Tes dernières actualités et alertes" badge={hasUnreadNotifications} />
        <LinkBlock title={language.profile.links.edit.title} url={PROFILE_EDIT_PAGE} description={language.profile.links.edit.description} />
        <LinkBlock title={language.profile.links.about.title} url={ABOUT_PAGE} description={language.profile.links.about.description}/>

        <Block containerClassName="block-animation">
          <LogoutButton />
        </Block>

        <Separator className="mt-6" />

        <A href={PROFILE_DELETE_PAGE} className="text-red mt-4 inline-flex block-animation">Supprimer mon compte</A>
      </Container>
    </main>
  );
}
