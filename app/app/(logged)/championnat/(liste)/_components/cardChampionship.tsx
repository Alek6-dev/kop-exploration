"use client"

import { Separator } from "@/components/custom/separator";
import { CHAMPIONSHIP_LOBBY_PAGE, CHAMPIONSHIP_RANKING_PAGE, CHAMPIONSHIP_SILLYSEASON_PAGE, CHAMPIONSHIP_STRATEGY_PAGE } from "@/constants/routing";
import language from "@/messages/fr";
import { ChampionshipActivePlayerArray, ChampionshipActiveRacesArray } from "@/type/championship";
import IconClock from "@/public/assets/icons/small/clock.svg"
import IconArrowInCircle from "@/public/assets/icons/arrow-in-circle.svg"

export interface CardChampionshipProps {
    uuid: string,
    status: number,
    name: string,
    races: ChampionshipActiveRacesArray[];
    nextRace: number,
    totalRace: number,
    players?: ChampionshipActivePlayerArray[],
    className?: String,
    sessionId?: string | undefined,
}

const CardChampionship = ({ ...props }: CardChampionshipProps) => {

    // note : if championship is cancelled, the parent element is a div (there is no dedicated page for cancelled championships)
    let cancellationReason = "";
    if(props.status == 6) {
        cancellationReason = language.championship.list.cancellation_reason.manual;
    }
    if(props.status == 7) {
        cancellationReason = language.championship.list.cancellation_reason.missing_gp;
    }

    // Redirect user to the correct step of the championship
    let url = CHAMPIONSHIP_LOBBY_PAGE(props.uuid);
    if(props.status == 2 || props.status == 3) {
        url = CHAMPIONSHIP_SILLYSEASON_PAGE(props.uuid);
    }
    else if(props.status == 5) {
        url = CHAMPIONSHIP_STRATEGY_PAGE(props.uuid);
    }
    else if(props.status == 8) {
        url = CHAMPIONSHIP_RANKING_PAGE(props.uuid);
    }

    //console.log("props races : ", props.races);

    let currentRace: ChampionshipActiveRacesArray | null = null;
    if(props.races) {
        currentRace = props.races[props.nextRace - 1];
    }

    let playerPosition: number = 0;
    // Get player position in global ranking by finding his Uuid in the championship players data
    if(props.players) {
        playerPosition = props.players.findIndex((item: ChampionshipActivePlayerArray) => item.user.uuid === props.sessionId) + 1;
    }

    return(
        (cancellationReason === "" ?
            <a href={url} className="w-full mb-4 zone card-championship block-animation">
                <div className="relative px-4 py-3 rounded-lg overflow-hidden">
                    <h2 className="mb-[3px] h3">{props.name}</h2>
                    {props.status === 1 &&
                        <p className="text-primary text-tiny">{language.championship.list.status.awaiting_players}</p>
                    }
                    {(props.status === 2 || props.status === 3) &&
                        <span className="text-tiny text-primary">{language.championship.list.status.sillyseason}</span>
                    }
                    {props.status > 3 &&
                        <>
                            {(currentRace !== null && currentRace.status === 2) &&
                                <p className="text-primary text-tiny">{language.championship.list.status.strategy}</p>
                            }
                            {(currentRace !== null && currentRace.status === 3) || (currentRace !== null && currentRace.status === 4) &&
                                <p className="text-primary text-tiny">{language.championship.list.status.awaiting_results}</p>
                            }
                        </>
                    }
                    <div className="text-sm font-bold flex-v-centering text-gray pt-2 w-full mt-2 border-t border-white-6">
                        <span className="mr-1">{language.championship.list.gp}</span> {String(props.nextRace)}/{String(props.totalRace)}
                        {props.status > 3 &&
                            <>
                                <Separator className="separator-vertical mx-3 h-[12px]" />
                                <span className="mr-1">{language.championship.list.ranking}</span> {String(playerPosition)}e
                            </>
                        }
                    </div>
                </div>
            </a>
        :
            <div className="w-full mb-4 zone card-championship block-animation">
                <div className="relative p-4 rounded-lg overflow-hidden opacity-50">
                    <h2 className="mb-2 h3">{props.name}</h2>
                    <div className="text-sm text-red font-bold flex-v-centering">
                    {language.championship.list.cancelled} {cancellationReason}
                    </div>
                </div>
            </div>
        )
    )
}

export { CardChampionship };
