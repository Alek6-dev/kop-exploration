"use client"

import language from "@/messages/fr"
import { ChampionshipResultsDuelArray } from "../page"
import { Popin } from "@/components/custom/popin"
import { useState } from "react"
import { DuelResultsItem } from "./duelResultsItem"
import { PlayedBonus } from "../../_components/playedBonus"


interface DetailedResultsProps {
    resultsData: ChampionshipResultsDuelArray[],
}

const DetailedResults = ({resultsData}: DetailedResultsProps) => {
    // Manage popin bonus/malus content dynamically
    const [popinContent, setPopinContent] = useState<any>(null);
    const handlePopinContent = (newActiveState: any) => {
        //console.log("new active state : ", newActiveState);
        setPopinContent(newActiveState);
    }

    return(
        <>
            <div className="block-animation">
                {resultsData.map((duel: ChampionshipResultsDuelArray, index:number) => (
                    <DuelResultsItem key={index} duel={duel} handlePopinContent={handlePopinContent} />
                ))}
            </div>

            <Popin title={language.championship.results.played_bonus.title_duel} id="popin-bonus-malus" className="-ml-4">
                {popinContent != null && popinContent.map((bonus: any, index: number) => (
                    <PlayedBonus key={index} title={bonus.bonus.name} description={bonus.bonus.description} playedBy={bonus.player.name} active={bonus.balanceAfter !== null ? true : false}
                    impact={(bonus.balanceAfter - bonus.balanceBefore)} attribute={bonus.bonus.attribute} />
                ))}
            </Popin>
        </>
    )
}

export { DetailedResults }
