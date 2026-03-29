import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { ChampionshipTitle } from "../../components/ChampionshipTitle";
import { cookies } from "next/headers";
import { ChampionshipActiveRacesArray } from "@/type/championship";
import { RaceSelector } from "../[raceUuid]/_components/raceSelector";

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

    const selectedRace: ChampionshipActiveRacesArray = championshipData.races[0];

  return (
    <>
        <ChampionshipTitle name={championshipData.name} status={championshipData.status} uuid={uuid} raceIndex={0} raceUuid="no-results" />
        <Container className="mt-6 z-[2] block-animation">
            <RaceSelector selectedRace={selectedRace} races={championshipData.races} championshipUuid={uuid} />
        </Container>

        <Container className="block-animation">
            <Block containerClassName="mt-4 p-4">
                <div>{language.championship.results.no_results}</div>
            </Block>
        </Container>
    </>
  )
}
