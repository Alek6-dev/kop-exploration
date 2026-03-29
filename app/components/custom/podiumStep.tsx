"use client"

import Image from "next/image";
import { PodiumProps, PodiumPlayer } from "./podium";
import Link from "next/link";
import { useState } from "react";
import { OTHER_USER_PROFILE_PAGE } from '@/constants/routing';

export interface PodiumStepProps {
    podiumData: PodiumPlayer[],
    position: number,
}

const PodiumStep = ({ ...props }: PodiumStepProps ) => {
    const [equality, setEquality] = useState(0);

    const equalityPosition1 = props.podiumData.filter(x => x.position === 1);
    if(equalityPosition1.length > equality) {
        setEquality(equalityPosition1.length);
    }
    const equalityPosition2 = props.podiumData.filter(x => x.position === 2);
    if(equalityPosition2.length > equality) {
        setEquality(equalityPosition2.length);
    }
    const equalityPosition3 = props.podiumData.filter(x => x.position === 3);
    if(equalityPosition3.length > equality) {
        setEquality(equalityPosition3.length);
    }

    return (
        <div className={"relative z-10 grow-0 flex-1 equality-" + equality}>
            <div className="mx-auto z-10 relative flex justify-center px-2 gap-x-1 -mb-1">
                {props.podiumData.map((player: PodiumPlayer) => (
                    player.position === props.position && (
                        <Link href={OTHER_USER_PROFILE_PAGE(player.userUuid)} key={player.userUuid} className="flex-1"><Image src={player.helmet} alt="Helmet" width={60} height={80} className="mx-auto -mb-2 z-10 relative" /></Link>
                    )
                ))}
            </div>
            <div className={"flex flex-col items-center pb-2 px-2 podium-part pt-4 " + (props.position === 2 ? "gradient-silver rounded-tl-lg" : "") +  (props.position === 1 ? "gradient-gold rounded-t-lg" : "") + (props.position === 3 ? "gradient-bronze rounded-tr-lg" : "")}>
                <div className="mt-auto flex flex-col h4 text-white text-center ">
                    {props.podiumData.map((player: PodiumPlayer) => (
                        player.position === props.position && (

                                <span className="truncate relative w-full leading-tight shrink-0" key={player.userUuid}>{player.name}</span>

                        )
                    ))}
                </div>
            </div>
        </div>
    )

}


export { PodiumStep };
