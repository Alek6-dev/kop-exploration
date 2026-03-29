"use client"

import IconCrown from "@/public/assets/icons/crown.svg";
import { Counter } from "@/components/custom/counter";
import language from "@/messages/fr";
import { ChampionshipActiveDuelOpponentArray } from "@/type/championship";
import { OTHER_USER_PROFILE_PAGE } from "@/constants/routing";

interface DuelPlayerProps {
    player: ChampionshipActiveDuelOpponentArray,
    points: number,
    winner: boolean,
    playerNumber: string,
}

const DuelPlayer = ({ player, points, winner, playerNumber } : DuelPlayerProps) => {
    const playerPoints = (points > 0 ? "+" : "") + points + " " + (points > 0 ? language.championship.results.pts : language.championship.results.pt);

    //console.log('player in duelPlayer : ', player);

    return (
        <div className={"w-1/2 flex flex-col items-center text-center svg-black " + (winner ? "" : "opacity-50")}>
            <picture className="w-full h-18 flex flex-centering duel-car" style={{ backgroundColor: player.helmetColor }}>
                <img src={`${process.env.NEXT_PUBLIC_API_URL + '/' + player.helmetImageUrl1}`} className="relative block max-w-none -mt-1" alt="" width="44" height="52" />
            </picture>
            {winner &&
                <IconCrown className={"-mt-6 mb-1 z-[1] " + (playerNumber == "1" ? "self-start ml-2" : "self-end  mr-2")} />
            }
            <Counter counterValue={playerPoints} className="px-1 bg-white border-[3px] border-color-black text-black -mt-2 mb-1 z-[2]" />
            <a href={OTHER_USER_PROFILE_PAGE(player.userUuid)}><b>{player.name}</b></a>
        </div>
    )
}

export { DuelPlayer }
