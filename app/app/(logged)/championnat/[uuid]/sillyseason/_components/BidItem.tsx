import Image from "next/image";
import language from "@/messages/fr";
import { Input } from "@/components/ui/input";
import { Checkbox } from "@/components/ui/checkbox";
import { bettingRoundDriverArray, bettingRoundTeamArray } from "./BettingRoundForm";
import { ShowItemBid } from "./ShowItemBid";

export interface BidItemProps {
    item: any,
    type?: "driver"|"team",
    bidSubmitted: boolean,
    itemSelected: Array<String>,
    remainingItem: number,
    bettingRoundDrivers?: bettingRoundDriverArray[]|null,
    bettingRoundTeam?: bettingRoundTeamArray[]|null,
    handleClick: Function,
}

const BidItem = ({ item, type="driver", bidSubmitted, itemSelected, remainingItem, bettingRoundDrivers=null, bettingRoundTeam=null, handleClick }: BidItemProps) => {
    const driverType = type === "driver";

    return(
        <div className="flex items-center border-t border-white-6 h-12">
            <Image src={`${process.env.NEXT_PUBLIC_API_URL + '/' + item.image}`} alt={item.name} quality={100} width={50} height={50} className="self-end" />
            <div className="ml-2">
                <h4 className="h4">{item.name}</h4>
                <div className="text-sm">
                    {type === "driver" &&
                        <span className="mr-[4px]">{item.team.name}.</span>
                    }
                    <span className="text-gray inline-flex items-center">
                        Min : {item.minValue}
                        <Image src="/assets/icons/money/m.svg" alt="" quality={100} width={12} height={12} className="ml-1" />
                    </span>
                </div>
            </div>
            {bidSubmitted ?
                <ShowItemBid bettingRoundDrivers={bettingRoundDrivers} bettingRoundTeam={bettingRoundTeam} itemUuid={item.uuid} />
            :
                <div className="ml-auto mr-4 flex items-center">
                    <Input
                        className="w-14 h-8 px-2 input-bid hidden"
                        type="number"
                        max={999}
                        min={item.minValue}
                        placeholder={item.minValue}
                        id={item.uuid}
                    />
                    {driverType ?
                        <Checkbox id={"checkbox-"+item.uuid} className="ml-2 checkbox-driver-bid" onClick={(e) => handleClick(e, item.uuid, "driver")} disabled={itemSelected[0] !== ("checkbox-"+item.uuid) && itemSelected[1] !== ("checkbox-"+item.uuid) && itemSelected.length > remainingItem - 1}/>
                    :
                        <Checkbox id={"checkbox-"+item.uuid} className="ml-2 checkbox-team-bid" onClick={(e) => handleClick(e, item.uuid, "team")} disabled={itemSelected[0] !== ("checkbox-"+item.uuid) && itemSelected.length > 0}/>
                    }
                </div>
            }
        </div>
    )
}

export { BidItem };
