import React, { useState } from "react";
import { Container } from "@/components/custom/container";
import { CHAMPIONSHIP_DUEL_PAGE, CHAMPIONSHIP_RESULTS_PAGE, LOGIN_PAGE } from "@/constants/routing";
import { ChampionshipResultsStrategyArray, ChampionshipResultsStrategyDriverArray } from "../page";
import { RaceSelector } from "../_components/raceSelector";
import { DuelResultsItem } from "./_components/duelResultsItem";
import { TabsAsPage } from "@/components/custom/tabsAsPage";
import language from "@/messages/fr";
import { cookies } from "next/headers";
import { ChampionshipActiveRacesArray, ChampionshipActiveDuelOpponentArray } from "@/type/championship";
import { ChampionshipTitle } from "../../../components/ChampionshipTitle";
import { getSession } from "@/lib/security";
import { DetailedResults } from "./_components/detailedResults";
import { BonusApplicationArray } from "@/type/bonus";

export interface ChampionshipResultsDuelArray {
    uuid: string,
    player1: ChampionshipActiveDuelOpponentArray,
    player2: ChampionshipActiveDuelOpponentArray,
    playerDriverPerformance1: ChampionshipResultsStrategyDriverArray,
    playerDriverPerformance2: ChampionshipResultsStrategyDriverArray,
    pointsPlayer1: number,
    pointsPlayer2: number,
    scorePlayer1: number,
    scorePlayer2: number,
    bonusApplicationByPlayer1: BonusApplicationArray[],
    bonusApplicationByPlayer2: BonusApplicationArray[],
    bonusesAppliedToPlayer1: BonusApplicationArray[],
    bonusesAppliedToPlayer2: BonusApplicationArray[],
}

export default async function DuelResults({params : { uuid, raceUuid }}: {params: { uuid: string, raceUuid: string}}) {
    const token = cookies().get("session")?.value;
    const headers = {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    }

    const fetchData = async () => {
      try {
        const responsesJSON = await Promise.all([
          fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}`, { headers }),
          fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/race/${raceUuid}/result`, { headers }),
        ]);
        const [championshipData, resultsData] = await Promise.all(responsesJSON.map(r => r.json()));
        return [championshipData, resultsData];
      } catch (err) {
        throw err;
      }
    };

    const dataArray = await fetchData();
    const championshipData = dataArray[0];
    const resultsData = dataArray[1];

    //console.log("duel championship data : ", championshipData);
    //console.log("duel race results : ", resultsData.duels[0].bonusApplication);

    const selectedRace: ChampionshipActiveRacesArray = championshipData.races.findLast((item: ChampionshipActiveRacesArray) => item.uuid === raceUuid) ?? championshipData.races.findLast((item: ChampionshipActiveRacesArray) => item.uuid === raceUuid);

    const raceIndex: number = championshipData.countRacesOver;

    // Get player position in global ranking by finding his Uuid in the resultsData
    const session = await getSession();
    const playerIndexPosition: number = resultsData.strategies.findIndex((item: ChampionshipResultsStrategyArray) => item.player.userUuid === session?.id);
    const playerPosition: number = resultsData.strategies[playerIndexPosition].player.position;


    const tabs = [
        {
          label: language.championship.results.tabs.results,
          url: CHAMPIONSHIP_RESULTS_PAGE(uuid, raceUuid),
          id: "tab-results",
        },
        {
          label: language.championship.results.tabs.duel,
          url: CHAMPIONSHIP_DUEL_PAGE(uuid, raceUuid),
          id: "tab-duels",
        },
    ]

    return(
        <>
          <ChampionshipTitle name={championshipData.name} status={championshipData.status} uuid={uuid} raceIndex={raceIndex} raceUuid={raceUuid} ranking={playerPosition} />
          <Container className="mt-6">
              <div className="relative z-[2] block-animation">
                  <RaceSelector selectedRace={selectedRace} races={championshipData.races} championshipUuid={uuid} />
              </div>
              <TabsAsPage tabs={tabs} defaultActive={tabs[0].id} className="mt-2 mb-4 bg-black block-animation" />
              <DetailedResults resultsData={resultsData.duels} />
          </Container>
        </>
    )
}
