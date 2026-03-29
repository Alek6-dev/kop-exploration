"use client"

import { ReactNode } from "react";
import { cn } from "@/lib/utils";
import { Separator } from "./separator";
import IconCross from "@/public/assets/icons/small/cross.svg";

export interface PopinProps {
    children: ReactNode,
    title: String,
    className?: String,
    buyPopin?: boolean,
    id: string;
}

const Popin = ({ children, buyPopin = false, id = 'popin', ...props }: PopinProps ) => {
    const handleClick = () => {
        document.getElementById(id)?.classList.remove("!translate-y-0");
        // Avoid scrolling and interaction of body when popin is open
        document.body.classList.toggle('touch-none');
        document.body.classList.toggle('overflow-hidden');
    };

    return (
        <div id={id} className={cn("bg-[#000000] rounded-t-lg p-6 fixed bottom-0 w-full max-h-screen overflow-scroll z-20 text-gray global-transition translate-y-full popin", props.className)}>
            {buyPopin ?
                <div className="p-3 absolute top-1 right-1 z-10 svg-black" onClick={handleClick}>
                    <IconCross/>
                </div>
                :
                <>
                    <div className="flex justify-between pb-2 items-start">
                        <h3 className="h3 text-primary">{props.title}</h3>
                        <div className="pl-3 svg-primary pt-1" onClick={handleClick}>
                            <IconCross/>
                        </div>
                    </div>
                    <Separator className="mb-5 mt-3 bg-gray opacity-25" />
                </>
            }
            {children}
        </div>
    )
}

export { Popin };
