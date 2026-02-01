import { getSession } from "@/lib/security";
import React, { useState } from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { redirect } from "next/navigation";
import { CHAMPIONSHIP_DUEL_PAGE, CHAMPIONSHIP_RESULTS_PAGE, LOGIN_PAGE, NOTFOUND_PAGE } from "@/constants/routing";
import { RaceSelector } from "./_components/raceSelector";
import { PlayerResults } from "./_components/playerResults";
import { TabsAsPage } from "@/components/custom/tabsAsPage";
import { Podium } from "@/components/custom/podium";
import { ChampionshipTitle } from "../../components/ChampionshipTitle";
import { cookies } from "next/headers";
import { ChampionshipActiveRacesArray, ChampionshipResultsDriverArray, ChampionshipResultsPlayerArray, ChampionshipResultsTeamArray } from "@/type/championship";
import { Popin } from "@/components/custom/popin";
import { PlayedBonus } from "./_components/playedBonus";
import { DetailedResults } from "./_components/detailedResults";
import { BonusApplicationArray } from "@/type/bonus";
import { ChampionshipResultsDuelArray } from "./duels/page";


export interface ChampionshipResultsStrategyDriverArray {
  driverResource: ChampionshipResultsDriverArray,
  qualificationPositionPoint: number,
  racePositionPoint: number,
  sprintPositionPoint: number,
  positionGain: number,
  position: string,
  points: number | null,
  score: number,
  scoreWithBonus: number,
  reference : {
    qualificationPositionPoint: number,
    racePositionPoint: number,
    sprintPositionPoint: number,
    positionGain: number,
    position: string,
    points: number | null,
    score: number,
  }
}

export interface ChampionshipResultsStrategyTeamArray {
  teamResource: ChampionshipResultsTeamArray,
  teamMultiplier: number,
  position: string,
  points: number | null,
  score: number,
  reference : {
    teamMultiplier: number,
    position: string,
    points: number | null,
    score: number,
  }
}

export interface ChampionshipResultsStrategyArray {
  uuid: string,
  player: ChampionshipResultsPlayerArray,
  driver: ChampionshipResultsDriverArray,
  driverPerformances: ChampionshipResultsStrategyDriverArray[],
  teamPerformance: ChampionshipResultsStrategyTeamArray,
  position: number,
  score: number,
  points: number,
  bonusApplication: BonusApplicationArray[],
  bonusesApplied: BonusApplicationArray[],
}

export interface ChampionshipResultsArray {
  strategies: ChampionshipResultsStrategyArray[],
  duel: ChampionshipResultsDuelArray[],
}

export default async function RaceResults({params : { uuid, raceUuid }}: {params: { uuid: string, raceUuid: string}}) {
  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }

  const resChampionship = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}`, { headers });
  const championshipData = await resChampionship.json();
  if (!resChampionship.ok) {
    throw new Error(championshipData.message);
  }
  //console.log("championship data : ", championshipData);

  const resResults = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/race/${raceUuid}/result`, { headers });
  const resultsData = await resResults.json();

  if (!resResults.ok) {
    throw new Error(resultsData.message);
  }

  // console.log("results data : ", resultsData.strategies[2].bonusesApplied);
  // console.log("results data performance : ", resultsData.strategies[2].driverPerformances);

  // Get the raceUuid in URL parameters and find the corresponding race in championshipData
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

  const podium = [];

  for (let i = 0; i < 4; i++) {
    if(i < 3 || resultsData.strategies[2].position === resultsData.strategies[3].position) {
      podium.push({
        position: resultsData.strategies[i].position,
        userUuid: resultsData.strategies[i].player.userUuid,
        name: resultsData.strategies[i].player.name,
        helmet: resultsData.strategies[i].player.helmetImageUrl1 ? process.env.NEXT_PUBLIC_API_URL+"/"+resultsData.strategies[i].player.helmetImageUrl1 : "/assets/images/temp/helmet-2.png",
      });
    }
  }

  return(
    <>
      <ChampionshipTitle name={championshipData.name} status={championshipData.status} uuid={uuid} raceIndex={raceIndex} raceUuid={raceUuid} ranking={playerPosition} />
      <Container className="mt-6 z-[2] block-animation">
        <RaceSelector selectedRace={selectedRace} races={championshipData.races} championshipUuid={uuid} />
        <TabsAsPage tabs={tabs} defaultActive={tabs[0].id} className="mt-2 mb-4 bg-black block-animation" />
      </Container>

      <Podium podiumData={podium} />

      <DetailedResults resultsData={resultsData} />

    </>
  )
}
