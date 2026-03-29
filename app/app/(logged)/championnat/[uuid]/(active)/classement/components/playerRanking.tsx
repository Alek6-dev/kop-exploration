"use client"

import IconDropdownArrow from "@/public/assets/icons/small/dropdown-arrow.svg";
import IconProfile from "@/public/assets/icons/small/profile.svg";
import { Block } from "@/components/custom/block";
import { Button } from "@/components/ui/button";
import { CarInListing } from "@/components/custom/carInListing";
import language from "@/messages/fr";
import { useState } from "react";
import { OTHER_USER_PROFILE_PAGE } from "@/constants/routing";

interface PlayerRankingProps {
    playerName: string,
    playerTeam: string|null,
    playerDriver1: string,
    playerDriver2: string,
    playerUserUuid: string,
    playerPoints: number,
    playerScore: number,
    playerPosition: number,
    playerCar: string,
    playerCarColor: string,
    index: number
}

const PlayerRanking = ({ playerUserUuid, playerName, playerTeam, playerDriver1, playerDriver2, playerPoints, playerScore, playerPosition, playerCar, playerCarColor, index } : PlayerRankingProps) => {
    const point = playerPoints < 1 ? language.championship.results.pt : language.championship.results.pts;

    return (
        // <Block containerClassName={"mb-4 rounded-r-none block-animation rank-"+ (index+1) }>
        //     {/* <div className="flex justify-between items-center w-full">
        //         <div className="flex py-3">
        //             <div className="flex-centering rounded-r-lg h-9 w-7 mr-3 shrink-0 rank-number"><b>{index+1}</b></div>
        //             <div className="flex flex-wrap">
        //                 <h3 className="text-medium w-full mb-[3px] leading-none"><b>{playerName}</b></h3>
        //                 <span className="text-black px-1 rounded-lg bg-gray"><b>{playerPoints} {point}</b></span>
        //             </div>
        //         </div>
        //         <div className="flex items-center">
        //             <Button variant="light" size="xs" className="-mr-3 w-[32px] z-[1] bg-black svg-gray" asChild>
        //                 <a href={OTHER_USER_PROFILE_PAGE(playerUserUuid)}>
        //                 <IconProfile />
        //                 </a>
        //             </Button>
        //             <a href={OTHER_USER_PROFILE_PAGE(playerUserUuid)}><CarInListing car={playerCar} carColor={playerCarColor} width={80} className="w-16 h-12 overflow-hidden" /></a>
        //         </div>
        //     </div> */}

        //     <div className="flex justify-between items-center w-full">
        //         <div className="flex items-center">
        //             <div className="flex-centering py-2 flex-col rounded-r-lg w-12 mr-3 shrink-0 rank-number h-full">
        //                 <b className="text-md p-1 bg-black rounded-full w-5 h-5 flex-centering text-white">{index+1}</b>
        //                 <span className="pt-1 text-tiny"><b>{playerPoints} {point}</b></span>
        //             </div>
        //             <div className="flex flex-wrap  py-3">
        //                 <h3 className="text-medium w-full mb-[3px] leading-none"><b>{playerName}</b></h3>

        //                 <span className="w-full text-tiny text-gray mt-1 leading-snug">{playerDriver1}, {playerDriver2} <br/> {playerTeam}</span>
        //             </div>
        //         </div>
        //         <div className="flex items-center py-3">
        //             <Button variant="light" size="xs" className="-mr-3 w-[32px] z-[1] bg-black svg-gray" asChild>
        //                 <a href={OTHER_USER_PROFILE_PAGE(playerUserUuid)}>
        //                 <IconProfile />
        //                 </a>
        //             </Button>
        //             <a href={OTHER_USER_PROFILE_PAGE(playerUserUuid)}><CarInListing car={playerCar} carColor={playerCarColor} width={80} className="w-16 h-12 overflow-hidden" /></a>
        //         </div>
        //     </div>
        // </Block>

        <div className={"flex justify-between border-t border-white-6 w-full py-2 items-center block-animation rank-"+ (playerPosition) }>
            <div className="rounded-full h-5 w-5 flex-centering font-bold flex-shrink-0 flex-grow-0 ml-3">{playerPosition}</div>
            <b className="ml-2 pr-2 leading-tight"><a href={OTHER_USER_PROFILE_PAGE(playerUserUuid)}>{playerName}</a></b>
            <div className="ml-auto mr-2 flex-shrink-0 flex flex-col text-right">
                <b className="">{playerPoints} <span className="text-[9px]">{point}</span></b>
                <span className="text-[10px] leading-tight text-gray">{language.championship.results.scorecolon} {playerScore / 10}</span>
            </div>
            <div className="flex items-center">
                <Button variant="light" size="xs" className="-mr-3 w-[32px] z-[1] scale-75 bg-black svg-gray" asChild>
                    <a href={OTHER_USER_PROFILE_PAGE(playerUserUuid)}>
                    <IconProfile />
                    </a>
                </Button>
                <a href={OTHER_USER_PROFILE_PAGE(playerUserUuid)}><CarInListing car={playerCar} carColor={playerCarColor} width={50} className="w-10 h-8 overflow-hidden" pictureClassName="translate-x-[12px]"  /></a>
            </div>
        </div>
    )
}

export { PlayerRanking }
