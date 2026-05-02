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
    badge?: string,
}

const HubButton = ({ title, url, className, icon, iconClassName, badge }: HubButtonProps ) => {
    return (
        <a href={url} className={cn("relative rounded-lg aspect-square block-animation", className)}>
            {badge && (
                <span className="absolute top-2 right-2 bg-primary text-black text-[9px] font-bold uppercase tracking-wider px-[6px] py-[2px] rounded-full z-10">
                    {badge}
                </span>
            )}
            <div className="relative pt-4 pl-4">
                <h2 className="relative text-black h3">{title}</h2>
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
