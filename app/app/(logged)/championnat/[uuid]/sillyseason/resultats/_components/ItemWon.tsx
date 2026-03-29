import { ResultsArray } from "../page";
import Image from "next/image";
import { Counter } from "@/components/custom/counter";
import language from "@/messages/fr";

export interface ItemWonProps {
    result: ResultsArray,
    lastRound: Number,
    type: 'driver' | 'team',
}

const ItemWon = ({ result, lastRound, type }: ItemWonProps) => {
    const bgColor = result.color ? result.color : "rgba(255, 255, 255, 0.06)";
    let resultRound = result.assignBySystem ? (result.round ?? 0) - 1 : (result.round ?? 0);

    return(
        <div className="flex items-center px-4 pb-2 justify-between">
            <div className={"flex items-center" + (resultRound == lastRound ? " font-bold text-primary" : "")}>
                <div className={"rounded-full mr-2 h-4 w-4 overflow-hidden " +  (result.color ? "gradient-avatar-"+type : "") + (type === "team" ? " p-[2px]" : "")} style={{ backgroundColor: bgColor }}>
                    <Image src={`${process.env.NEXT_PUBLIC_API_URL + '/' + result.image}`} alt="" quality={100} width={20} height={20} className="mr-2" />
                </div>
                {result.name}
                {resultRound == lastRound &&
                    <Counter className="ml-2 bg-primary px-1 !text-[10px] text-black leading-none h-3 translate-y-[1px]" counterValue="new" />
                }
            </div>
            <div className="flex baseline">
                <div className="flex items-center justify-end w-10 mr-4">
                    {result.amount}
                    <Image src="/assets/icons/money/m.svg" alt="" quality={100} width={12} height={12} className="ml-1" />
                </div>
                {language.championship.sillyseason_results.round} {resultRound}
            </div>
        </div>
    )
}

export { ItemWon };
