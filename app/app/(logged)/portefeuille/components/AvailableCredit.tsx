"use client"

import language from "@/messages/fr";
import { Block } from "@/components/custom/block";
import Image from "next/image";

interface AvailableCreditsProps {
    credits: number
}

const AvailableCredits = ({credits}: AvailableCreditsProps) => {
    return (
        <Block containerClassName="block-animation" childClassName="p-4 flex-row items-center">
            <div className="relative w-8 h-8">
                <Image
                    src="/assets/icons/money/kop.svg"
                    alt=""
                    quality={100}
                    width={40}
                    height={40}
                />
            </div>
            <div className="ml-3">
                <p className="h4 mb-[2px]">{language.wallet.available}</p>
                <p className="h3 text-primary">{credits}</p>
            </div>
        </Block>
    )
}

export { AvailableCredits };
