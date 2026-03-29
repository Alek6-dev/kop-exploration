import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { redirect } from "next/navigation";
import { NOTFOUND_PAGE } from "@/constants/routing";
import { cookies } from "next/headers";
import { Paddock } from "@/components/custom/paddock";
import { Block } from "@/components/custom/block";
import { statsData } from "../fakeData";
import { Stats, StatsArray } from "@/components/custom/stats";

export default async function OtherUserProfile({params : { uuid }}: {params: { uuid: string }}) {
    const token = cookies().get("session")?.value;
    const headers = {
        Authorization: `${"Bearer " + token}`,
        "Content-Type": "application/json",
    }

    try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${uuid}`, { headers });
        const userData = await res.json();

        if (!res.ok) {
          throw new Error(userData.message);
        }

        const resStats = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/statistics/user/${userData.uuid}`, { headers });
        const stats = await resStats.json();

        //console.log("stats : ", stats);

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

        // Translations with variables
        const title = language.profile.other_user_profile.title
        .replace("{pseudo}", userData.pseudo);

        return (
            <main>
                <Container>
                    <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: title,
                    }}
                    ></h1>
                </Container>

                <div className="mt-6">
                    <Paddock car={userData.carCosmetic ? userData.carCosmetic.image1 : ""} helmet={userData.helmetCosmetic ? userData.helmetCosmetic.image2 : ""} carColor={userData.carCosmetic ? userData.carCosmetic.color : "#00FFFF"} />
                    <Container className="-mt-12">
                        <h2 className="h2 mb-4 block-animation">{language.profile.other_user_profile.stats}</h2>
                        <Block containerClassName="block-animation" childClassName="grid grid-cols-2">
                        {!resStats.ok ?
                            <span className="p-4 col-span-2 text-red">Les statistiques n’ont pas pu être chargées.</span>
                        :
                            statsData.map((stat: StatsArray, index: number) => (
                                <Stats key={index} label={stat.label} value={stat.value} maxValue={stat.maxValue} />
                            ))
                        }
                        </Block>
                    </Container>
                </div>
            </main>
        )
    } catch (e: any) {
        redirect(NOTFOUND_PAGE);
    }
}
