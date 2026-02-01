"use client"

import IconDropdownArrow from "@/public/assets/icons/small/dropdown-arrow.svg";
import IconProfile from "@/public/assets/icons/small/profile.svg";
import { ChampionshipResultsStrategyArray } from "../page";
import { Counter } from "@/components/custom/counter";
import { Block } from "@/components/custom/block";
import { Button } from "@/components/ui/button";
import { CarInListing } from "@/components/custom/carInListing";
import language from "@/messages/fr";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { DriverResultsLine } from "./driverResultsLine";
import { useState } from "react";
import { OTHER_USER_PROFILE_PAGE } from "@/constants/routing";
import { TeamResultsLine } from "./teamResultsLine";
import { PopinOpener } from "@/components/custom/popinOpener";
import { BonusApplicationArray } from "@/type/bonus";

interface PlayerResultsProps {
    playerData: ChampionshipResultsStrategyArray,
    handlePopinContent: any,
    position: number
}

const PlayerResults = ({ playerData, handlePopinContent, position } : PlayerResultsProps) => {
    const { player } = playerData;
    const [detailsVisible, setDetailsVisible] = useState(false);

    const hasSprintResults = playerData.driverPerformances[0].sprintPositionPoint != null;

    //console.log("bonus applied in playersResults : ", playerData.player.name, playerData.bonusesApplied);
    //console.log("team performance in playersResults : ", playerData.player.name, playerData.teamPerformance);
    //console.log("playerData in playersResults : ", playerData.bonusesApplied.length);

    let hasBonusesApplied: boolean = false;
    let bonusesApplied:BonusApplicationArray[] = [];
    if(Array.isArray(playerData.bonusesApplied) && playerData.bonusesApplied.length > 0) {
        hasBonusesApplied = true;
        bonusesApplied = playerData.bonusesApplied;
    }
    if(!Array.isArray(playerData.bonusesApplied)) {
        hasBonusesApplied = true;
        bonusesApplied = Object.values(playerData.bonusesApplied);
    }

    const iselectedDriver = playerData.driver === null ? null : playerData.driver.uuid;

    return (
        <Block containerClassName={"mb-4 rounded-r-none block-animation rank-"+ position }>
            <div className="flex justify-between items-center w-full">
                <div className="flex py-3" onClick={() => setDetailsVisible(!detailsVisible)}>
                    <div className="flex-centering rounded-r-lg h-9 w-7 mr-3 shrink-0 rank-number"><b>{position}</b></div>
                    <div className="flex flex-wrap">
                        <h3 className="text-medium w-full mb-[6px] leading-none"><b>{player.name}</b></h3>
                        <Counter counterValue={"+"+playerData.points+" PTS"} className="bg-gray text-black mr-2 flex-initial px-1" />
                        <span className="text-primary"><b>{language.championship.results.scorecolon} {playerData.score / 10}</b></span>
                    </div>
                </div>
                <div className="flex items-center">
                    <Button variant="light" size="xs" className="mr-2 w-[32px] z-[1] bg-black svg-gray" onClick={() => setDetailsVisible(!detailsVisible)}><IconDropdownArrow /></Button>
                    <Button variant="light" size="xs" className="-mr-3 w-[32px] z-[1] bg-black svg-gray" asChild>
                        <a href={OTHER_USER_PROFILE_PAGE(player.userUuid)}>
                            <IconProfile />
                        </a>
                    </Button>
                    <a href={OTHER_USER_PROFILE_PAGE(player.userUuid)}><CarInListing car={player.carImageUrl1} carColor={player.carColor} width={80} className="w-16 h-12 overflow-hidden" /></a>
                </div>
            </div>
            <div className={"px-4 pb-2 " + (detailsVisible ? "" : "hidden")}>
                <Table className="results-table">
                    <TableHeader className="border-spacing-0">
                        <TableRow className="!border-0 text-sm text-gray">
                            <TableHead></TableHead>
                            <TableHead></TableHead>
                            <TableHead>{language.championship.results.pq}</TableHead>
                            <TableHead>{language.championship.results.pc}</TableHead>
                            {hasSprintResults &&
                                <TableHead>{language.championship.results.pcs}</TableHead>
                            }
                            <TableHead>{language.championship.results.gpo}</TableHead>
                            <TableHead>{language.championship.results.pp}</TableHead>
                            <TableHead>{language.championship.results.score}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <DriverResultsLine data={playerData.driverPerformances[0]} selectedDriver={iselectedDriver} hasSprintResults={hasSprintResults} />
                        <DriverResultsLine data={playerData.driverPerformances[1]} selectedDriver={iselectedDriver} hasSprintResults={hasSprintResults} />
                        <TeamResultsLine data={playerData.teamPerformance} hasSprintResults={hasSprintResults} />
                    </TableBody>
                </Table>
                {hasBonusesApplied &&
                    <PopinOpener type="button" popinId="popin-bonus-malus" className="my-1" title={language.championship.results.cta_bonus} handlePopinContent={handlePopinContent} bonusesApplied={bonusesApplied} />
                }
            </div>
        </Block>
    )
}

export { PlayerResults }
