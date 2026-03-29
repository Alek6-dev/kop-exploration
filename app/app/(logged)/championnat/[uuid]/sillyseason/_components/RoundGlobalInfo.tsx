"use client"

import language from "@/messages/fr";
import { Countdown } from "@/components/custom/countdown";
import { useState } from "react";
import { PopinOpener } from "@/components/custom/popinOpener";
import { CHAMPIONSHIP_LOBBY_PAGE, CHAMPIONSHIP_SILLYSEASON_RESULTS_PAGE, CHAMPIONSHIP_STRATEGY_PAGE } from "@/constants/routing";
import { Button } from "@/components/ui/button";
import { usePathname, useRouter } from "next/navigation";

interface RoundGlobalInfoProps {
    uuid: string;
    bettingRound: number,
    roundEndDate: Date,
    roundEndDateString: string,
    timeRemaining: number,
    countPlayersWithBidOnCurrentRound: number,
    playersBettingThisRound: number,
    status: number;
}

export const RoundGlobalInfo = ({uuid, bettingRound, roundEndDate, roundEndDateString, timeRemaining, countPlayersWithBidOnCurrentRound, playersBettingThisRound, status}: RoundGlobalInfoProps) => {
    const router = useRouter();
    const pathname = usePathname();
    if(status === 1) {
       router.push(CHAMPIONSHIP_LOBBY_PAGE(uuid));
    }
    if(status === 3 || status === 4) {
        setTimeout(() => { router.refresh(); }, 15000);
    }
    if(status === 5) {
        router.push(CHAMPIONSHIP_STRATEGY_PAGE(uuid));
    }

    return(
        <div className="flex flex-col block-animation mb-4">
            <div className="items-start justify-between flex flex-nowrap w-full">
                <div className="grow">
                    <h2 className="h2 leading-none">{language.championship.sillyseason.bettingRound.title}{bettingRound}</h2>
                    <PopinOpener type="help-text" popinId="popin" title="Comment ça marche ?" className="mb-2 mt-1 text-sm" />
                </div>
                {bettingRound > 1 &&
                    <Button variant="light" size="sm" className="flex-initial translate-y-[2px] w-auto" asChild>
                        <a href={CHAMPIONSHIP_SILLYSEASON_RESULTS_PAGE(uuid)}>Résultats</a>
                    </Button>
                }
            </div>

            {status == 2 &&
                <>
                    <p className="text-sm text-primary font-bold">
                        {language.championship.sillyseason.bettingRound.time_left}
                        <Countdown roundEndDate={roundEndDate} timeRemaining={timeRemaining} /> ({language.championship.sillyseason.bettingRound.time_end} {roundEndDateString})
                    </p>
                    <p className="text-sm text-gray">
                        {countPlayersWithBidOnCurrentRound > 1 &&
                            <span>{countPlayersWithBidOnCurrentRound}/{playersBettingThisRound} {language.championship.sillyseason.bettingRound.state}</span>
                        }
                        {countPlayersWithBidOnCurrentRound === 1 &&
                            <span>{countPlayersWithBidOnCurrentRound}/{playersBettingThisRound} {language.championship.sillyseason.bettingRound.state_singular}</span>
                        }
                        {countPlayersWithBidOnCurrentRound === 0 &&
                            <span>{language.championship.sillyseason.bettingRound.state_empty}</span>
                        }
                    </p>
                </>
            }
            {status == 3 &&
                <p className="text-sm text-red font-bold">{language.championship.sillyseason.bettingRound.results_in_progress}</p>
            }
        </div>
    )
}
