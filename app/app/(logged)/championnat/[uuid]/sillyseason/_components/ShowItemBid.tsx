import language from "@/messages/fr"
import Image from "next/image";
import { bettingRoundDriverArray, bettingRoundTeamArray } from "./BettingRoundForm";

interface ShowItemBidProps {
    bettingRoundDrivers: bettingRoundDriverArray[]|null,
    bettingRoundTeam: bettingRoundTeamArray[]|null,
    itemUuid: string,
}

const getBidAmount = (bettingRoundDrivers: bettingRoundDriverArray[]|null, bettingRoundTeam: bettingRoundTeamArray[]|null, itemUuid: string) => {
    if(bettingRoundDrivers != null) {
        if(bettingRoundDrivers[0] != null && bettingRoundDrivers[0].driver.uuid == itemUuid) {
            return(bettingRoundDrivers[0].bidAmount);
        }
        if(bettingRoundDrivers[1] != null && bettingRoundDrivers[1].driver.uuid == itemUuid) {
            return(bettingRoundDrivers[1].bidAmount);
        }
        return null;
    }
    if(bettingRoundTeam != null) {
        if(bettingRoundTeam[0].team.uuid == itemUuid) {
            return(bettingRoundTeam[0].bidAmount);
        }
        return null;
    }
}

export const ShowItemBid = ({bettingRoundDrivers, bettingRoundTeam, itemUuid}: ShowItemBidProps) => {
    if(bettingRoundDrivers === null && bettingRoundTeam === null) {
        return null;
    }

    const bidAmount = getBidAmount(bettingRoundDrivers, bettingRoundTeam, itemUuid);

    return(
        <>
            {bidAmount &&
                <div className="flex flex-col shrink-0 items-end ml-auto mr-4 text-primary font-bold leading-tight text-sm">
                    {language.championship.sillyseason.bettingRound.your_bid}
                    <div className="inline-flex items-center">{bidAmount} <Image src="/assets/icons/money/m.svg" alt="" quality={100} width={12} height={12} className="ml-1" /></div>
                </div>
            }
        </>
    )
}
