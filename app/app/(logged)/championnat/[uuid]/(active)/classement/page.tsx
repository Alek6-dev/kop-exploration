import { getSession } from "@/lib/security";
import React, { useState } from "react";
import { cookies } from "next/headers";
import { CHAMPIONSHIP_SILLYSEASON_RESULTS_PAGE, NOTFOUND_PAGE } from "@/constants/routing";
import { redirect } from "next/navigation";
import { PlayerRanking } from "./components/playerRanking";
import { ChampionshipActivePlayerArray, ChampionshipActiveRacesArray } from "@/type/championship";
import { Podium } from "@/components/custom/podium";
import { ChampionshipTitle } from "../components/ChampionshipTitle";
import { Block } from "@/components/custom/block";
import { Container } from "@/components/custom/container";
import { A } from "@/components/custom/link";
import language from "@/messages/fr";

export default async function RaceResults({params : { uuid }}: {params: { uuid: string}}) {
    const session = await getSession();
    const token = cookies().get("session")?.value;
    const headers = {
        Authorization: `${"Bearer " + token}`,
        "Content-Type": "application/json",
    }

    try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}`, { headers });
        const championshipData = await res.json();

        if (!res.ok) {
          throw new Error(championshipData.message);
        }

        const { players } = championshipData;

        const showPodium = championshipData.status === 8 ? true : false;

        //console.log("classement championship data : ", players[2]);

        const raceIndex: number = championshipData.countRacesOver;
        const previousRace: ChampionshipActiveRacesArray = championshipData.races[raceIndex - 1];
        const previousRaceUuid = previousRace ? previousRace.uuid : "no-results";

        // Get player position in global ranking by finding his Uuid in the championship players data
        const playerPosition: number = players.findIndex((item: ChampionshipActivePlayerArray) => item.user.uuid === session?.id) + 1;


        const podium = [];
        if(showPodium) {
            for (let i = 0; i < 4; i++) {
                if(i < 3 || players[2].position === players[3].position) {
                    podium.push({
                    position: players[i].position,
                    userUuid: players[i].user.uuid,
                    name: players[i].name,
                    helmet: players[i].user.helmetCosmetic ? process.env.NEXT_PUBLIC_API_URL+"/"+players[i].user.helmetCosmetic.image1 : "/assets/images/temp/helmet-2.png",
                    });
                }
            }
        }

        return(
            <>
                <ChampionshipTitle name={championshipData.name} status={championshipData.status} uuid={uuid} raceIndex={raceIndex} raceUuid={previousRaceUuid} ranking={playerPosition} />
                <Container className="mt-6 block-animation">
                    <Block containerClassName="p-4" childClassName="block">
                        <h2 className="h3 mb-1">{language.championship.ranking.title}</h2>
                        <p className="text-gray">{language.championship.ranking.description}
                        <A href={CHAMPIONSHIP_SILLYSEASON_RESULTS_PAGE(uuid)} className="inline text-primary">{language.championship.ranking.link}</A>.</p>
                    </Block>
                </Container>

                {showPodium &&
                    <Podium podiumData={podium} />
                }

                <div className="pl-4 pt-6 block-animation">
                    <Block containerClassName={"relative overflow-hidden mb-2 rounded-r-none block-animation global-ranking"}>
                        {players.map((player: ChampionshipActivePlayerArray, index:number) => (
                            <PlayerRanking
                                key={index}
                                playerName={player.name}
                                playerTeam={player.selectedTeam.name}
                                playerDriver1={player.selectedDriver1.lastName}
                                playerDriver2={player.selectedDriver2.lastName}
                                playerUserUuid={player.user.uuid}
                                playerPoints={player.point ? player.point : 0}
                                playerScore={player.score ? player.score : 0}
                                playerPosition={player.position ?? 0}
                                playerCar={player.user.carCosmetic ? player.user.carCosmetic.image1 : ""}
                                playerCarColor={player.user.carCosmetic ? player.user.carCosmetic.color : ""}
                                index={index}
                            />
                        ))}
                    </Block>
                </div>
            </>
        )
    } catch (e: any) {
        redirect(NOTFOUND_PAGE);
    }
}
