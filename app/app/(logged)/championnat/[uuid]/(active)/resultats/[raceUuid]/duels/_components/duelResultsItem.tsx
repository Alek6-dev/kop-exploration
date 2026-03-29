"use client"

import { Block } from "@/components/custom/block";
import language from "@/messages/fr";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { DriverResultsLine } from "../../_components/driverResultsLine";
import { ChampionshipResultsDuelArray } from "../page";
import { DuelPlayer } from "./duelPlayer";
import IconVersus from "@/public/assets/icons/versus.svg";
import { PopinOpener } from "@/components/custom/popinOpener";
import { BonusApplicationArray } from "@/type/bonus";

interface DuelResultsProps {
    duel: ChampionshipResultsDuelArray,
    handlePopinContent: any,
}

const DuelResultsItem = ({ duel, handlePopinContent } : DuelResultsProps) => {
    const hasSprintResults = duel.playerDriverPerformance1.sprintPositionPoint != null;

    //console.log("duel in duelResultsItem : ", duel);

    const BonusAppliedToPlayer1 = duel.bonusesAppliedToPlayer1;
    const BonusAppliedToPlayer2 = Object.values(duel.bonusesAppliedToPlayer2);

    const bonusesApplied = [];
    if(BonusAppliedToPlayer1.length > 0) {
        for (let i = 0; i < BonusAppliedToPlayer1.length; i++) {
            bonusesApplied.push(BonusAppliedToPlayer1[i]);
        }
    }
    if(BonusAppliedToPlayer2.length > 0) {
        for (let i = 0; i < BonusAppliedToPlayer2.length; i++) {
            bonusesApplied.push(BonusAppliedToPlayer2[i]);
        }
    }

    //console.log("bonusesApplied in duelResultsItem : ", bonusesApplied);
    //console.log("duel in duelResultsItem : ", duel);

    return (
        <Block containerClassName="mb-4 overflow-hidden block-animation">
            <div className="flex">
                <DuelPlayer player={duel.player1} points={duel.pointsPlayer1} winner={duel.pointsPlayer1 > duel.pointsPlayer2 ? true : false} playerNumber="1" />
                <DuelPlayer player={duel.player2} points={duel.pointsPlayer2} winner={duel.pointsPlayer1 < duel.pointsPlayer2 ? true : false} playerNumber="2" />
                <div className="absolute w-[6px] h-18 bg-black -skew-x-[4deg] block top-0 left-1/2 -translate-x-1/2"></div>
                <div className="absolute top-5 left-1/2 -translate-x-1/2 rounded-full bg-black w-8 h-8 flex items-center justify-center svg-primary">
                    <IconVersus />
                </div>
            </div>
            <div className="px-4 pb-2 mt-2 pt-2 border-t border-white-6">
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
                        <DriverResultsLine data={duel.playerDriverPerformance1} selectedDriver="" hasSprintResults={hasSprintResults} className={duel.pointsPlayer1 > duel.pointsPlayer2 ? "" : "opacity-50"} />
                        <DriverResultsLine data={duel.playerDriverPerformance2} selectedDriver="" hasSprintResults={hasSprintResults} className={duel.pointsPlayer1 < duel.pointsPlayer2 ? "" : "opacity-50"}/>
                    </TableBody>
                </Table>
                <div className="flex flex-wrap items-center mt-2">
                    {bonusesApplied.length > 0 &&
                        <>
                        {bonusesApplied.map((bonus: BonusApplicationArray, index:number) => (
                            <div key="index" className="w-full mt-1 text-sm"><b>{bonus.player.name}</b> a joué le bonus <b>{bonus.bonus.name}</b>.</div>
                        ))}
                        <PopinOpener type="button" title={language.championship.results.cta_bonus} popinId="popin-bonus-malus" className="my-2" handlePopinContent={handlePopinContent} bonusesApplied={bonusesApplied} />
                        </>
                    }
                </div>
            </div>
        </Block>
    )
}

export { DuelResultsItem }
