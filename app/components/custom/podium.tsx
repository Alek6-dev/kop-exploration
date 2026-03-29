"use client"

import { Container } from "./container";
import { PodiumStep } from "./podiumStep";

export interface PodiumPlayer {
    position: number,
    userUuid: string,
    name: string,
    helmet: string,
}

export interface PodiumProps {
    ClassName?: string,
    podiumData: PodiumPlayer[],
}

const Podium = ({ ...props }: PodiumProps ) => {
    return (
        <Container className="mt-6 block-animation">
            <div className="zone grid grid-cols-3 items-end pt-4 gap-x-[1px]">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-44 h-36 podium-light"></div>

                <PodiumStep podiumData={props.podiumData} position={2} />
                <PodiumStep podiumData={props.podiumData} position={1} />
                <PodiumStep podiumData={props.podiumData} position={3} />
            </div>
        </Container>
    )
}

export { Podium };
