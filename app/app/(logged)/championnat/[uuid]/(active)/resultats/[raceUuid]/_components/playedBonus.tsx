import { Separator } from "@/components/custom/separator"
import language from "@/messages/fr"


export interface PlayedBonusProps {
    title: string,
    description: string,
    playedBy: string,
    active: boolean,
    impact: number,
    attribute: string,
}

const PlayedBonus = ({title, description, active, playedBy, impact, attribute}: PlayedBonusProps) => {

    return(
        <>
            <div className={"flex w-full justify-between mb-1" + (!active && " opacity-50")}>
                <div>
                    <h4 className={"h-4 mb-1 text-white text-medium font-bold" + (!active ? " line-through" : "")}>{title}</h4>
                    <p className="text-sm !mb-2">{language.championship.results.played_bonus.playedby} {playedBy}</p>
                    {!active ?
                        <p className="text-white">{language.championship.results.played_bonus.reimbursement}</p>
                    :
                        <p>{description}</p>
                    }
                </div>
                {active &&
                    <b className={"shrink-0 text-medium min-w-10 text-right" + (impact < 0 ? " text-red" :  " text-green")}>{attribute === "driver_score" ? impact : impact/10} {attribute === "driver_score" ? "PP" : "ME"}</b>
                }
            </div>
            <Separator className="bg-white/10 mb-3 last:hidden" />
        </>
    )
}

export { PlayedBonus }
