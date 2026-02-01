"use client"

import React from 'react';
import Image from 'next/image';
import { cn } from "@/lib/utils";
import IconArrowInCircle from "@/public/assets/icons/arrow-in-circle.svg"

export interface HubButtonProps {
    title: string,
    url: string,
    icon: string,
    className: string,
    iconClassName: string,
}

const HubButton = ({ title, url, className, icon, iconClassName }: HubButtonProps ) => {
    return (
        <a href={url} className={cn("relative rounded-lg aspect-square block-animation", className)}>
            <div className="relative pt-4 pl-4">
                <h2 className="relative text-black h3">{title}</h2>
                {title === "Quiz" && <p className="text-black text-sm font-bold">Coming soon</p>}
                <div className={cn("relative rounded-full h-[24px] w-[24px] flex-centering mt-2 bg-black", iconClassName)}>
                    <IconArrowInCircle />
                </div>
            </div>
            <div className="absolute right-2 bottom-[-2px]">
                <Image src={`/assets/icons/big/${icon}.svg`} alt="" quality={100} width={80} height={80} />
            </div>
        </a>
    )
}

export { HubButton };
