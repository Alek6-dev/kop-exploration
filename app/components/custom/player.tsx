import { cn } from "@/lib/utils";
import { ReactNode } from "react";
import { CarInListing } from "./carInListing";
import { ChampionshipPlayerUserArray, championshipPlayerCosmeticArray } from "@/type/championship";
import { OTHER_USER_PROFILE_PAGE } from "@/constants/routing";

export interface PlayerProps {
    name: String,
    user: ChampionshipPlayerUserArray,
    divClassName?: string,
    titleClassName?: string,
    carClassName?: string,
}

const Player = ({ ...props }: PlayerProps) => {
    return(
        <div className={cn("justify-between py-2 border-t flex-v-centering border-white-6", props.divClassName)}>
            <a href={OTHER_USER_PROFILE_PAGE(props.user.uuid)} className={cn("ml-4 font-bold", props.titleClassName)}>{props.name}</a>
            {props.user.carCosmetic &&
                <a href={OTHER_USER_PROFILE_PAGE(props.user.uuid)}><CarInListing width={80} car={props.user.carCosmetic.image1} carColor={props.user.carCosmetic.color} className={props.carClassName} /></a>
            }
        </div>
    )
}

export { Player };
