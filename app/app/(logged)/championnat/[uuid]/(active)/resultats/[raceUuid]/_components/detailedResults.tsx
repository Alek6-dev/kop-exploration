"use client"

import language from "@/messages/fr"
import { ChampionshipResultsArray, ChampionshipResultsStrategyArray } from "../page"
import { PlayerResults } from "./playerResults"
import { Popin } from "@/components/custom/popin"
import { PlayedBonus } from "./playedBonus"
import { useState } from "react"


interface DetailedResultsProps {
    resultsData: ChampionshipResultsArray,
}

const DetailedResults = ({resultsData}: DetailedResultsProps) => {
    // Manage popin bonus/malus content dynamically
    const [popinContent, setPopinContent] = useState<any>(null);
    const handlePopinContent = (newActiveState: any) => {
        //console.log("new active state : ", newActiveState);
        setPopinContent(newActiveState);
    }

   //console.log("resultsData in detailedResults : ", resultsData)

    return(
        <>
            <div className="ml-2 mt-2 pl-2 pt-2 overflow-hidden block-animation">
                {resultsData.strategies.map((player: ChampionshipResultsStrategyArray) => (
                    <PlayerResults key={player.uuid} playerData={player} position={player.position} handlePopinContent={handlePopinContent} />
                ))}
            </div>

            <Popin title={language.championship.results.played_bonus.title} id="popin-bonus-malus">
                {popinContent != null && popinContent.map((bonus: any, index: number) => (
                    <PlayedBonus key={index} title={bonus.bonus.name} description={bonus.bonus.description} playedBy={bonus.player.name} active={bonus.balanceAfter !== null ? true : false}
                    impact={(bonus.balanceAfter - bonus.balanceBefore)} attribute={bonus.bonus.attribute} />
                ))}
            </Popin>
        </>
    )
}

export { DetailedResults }
