"use client"

import { Tabs } from "@/components/custom/tabs";
import { ReactNode, useState } from "react";
import { CardChampionship } from "./_components/cardChampionship";
import { Container } from "@/components/custom/container";
import language from "@/messages/fr";
import { CHAMPIONSHIP_LISTING_FINISHED_PAGE, CHAMPIONSHIP_LISTING_PAGE, CHAMPIONSHIP_LOBBY_PAGE } from "@/constants/routing";
import { cn } from "@/lib/utils";

export interface ChampionshipsContentProps {
    data: any,
    sessionId: string | undefined,
}

const ChampionshipsContent = ({ ...props }: ChampionshipsContentProps) => {

    //console.log("data in content : ", props.data[0][0].races);

    const tabs = [
    {
        label: language.championship.list.tab.in_progress.label,
        id: "tab-in-progress",
        counter: props.data[0].length
    },
    {
        label: language.championship.list.tab.over.label,
        id: "tab-over",
        counter: props.data[1].length
    }]

    let [tabState, setTabState] = useState("tab-in-progress");
    const handleTabState = (newActiveState: string) => {
        setTabState(newActiveState);
    }

    return(
        <>
            <Tabs tabs={tabs} defaultActive={tabs[0].id} className="mt-2 sticky top-0 z-10 bg-black block-animation no-delay" changeState={handleTabState} />
            <Container className={"mt-6 mb-14 " + (tabState === "tab-in-progress" ? "" : "hidden")}>
            {props.data[0].map((championship: any) => (
                <CardChampionship key={championship.uuid} uuid={championship.uuid} status={championship.status} name={championship.name} players={championship.players} races={championship.races} nextRace={championship.countRacesOver + 1} totalRace={championship.numberOfRaces} sessionId={props.sessionId} />
            ))}
            </Container>
            <Container className={"mt-6 mb-14 " + (tabState === "tab-over" ? "" : "hidden")}>
            {props.data[1].map((championship: any) => (
                <CardChampionship key={championship.uuid} uuid={championship.uuid} status={championship.status} name={championship.name} players={championship.players} races={championship.races} nextRace={championship.countRacesOver} totalRace={championship.numberOfRaces} sessionId={props.sessionId} />
            ))}
            </Container>
        </>
    )
}

export { ChampionshipsContent };
