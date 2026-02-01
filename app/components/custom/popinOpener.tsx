"use client"

import Image from "next/image";
import { Button } from "../ui/button";

export interface PopinOpenerProps {
    type: "link"|"button"|"help"|"help-text",
    popinId: string,
    className?: string,
    title?: string,
    handlePopinContent?: any;
    bonusesApplied?: any,
}

const PopinOpener = ({ ...props }: PopinOpenerProps) => {
    function handlePopinDisplayState() {
        document.getElementById(props.popinId)?.classList.add("!translate-y-0");
        // Avoid scrolling and interaction of body when popin is open
        document.body.classList.toggle('touch-none');
        document.body.classList.toggle('overflow-hidden');

        if(props.handlePopinContent != undefined) {
            props.handlePopinContent(props.bonusesApplied);
        }
    }

    return (
        <>
            {props.type == "link" &&
                <button className={"link text-gray " + props.className} onClick={() => handlePopinDisplayState()}>Comment ça marche ?</button>
            }
            {props.type == "button" &&
                <Button variant="light" size="sm" onClick={() => handlePopinDisplayState()} className={props.className}>{props.title}</Button>
            }
            {props.type == "help" &&
                <div onClick={() => handlePopinDisplayState()} className={props.className}>
                    <Image
                        src="/assets/icons/help.svg"
                        alt=""
                        quality={100}
                        width={24}
                        height={24}
                        className="ml-1"
                    />
                </div>
            }
            {props.type == "help-text" &&
                <div onClick={() => handlePopinDisplayState()} className={"flex items-center " + props.className}>
                    <span className="border-b border-dotted border-white/30 leading-tight">{props.title}</span>
                    <Image
                        src="/assets/icons/help.svg"
                        alt=""
                        quality={100}
                        width={16}
                        height={16}
                        className="ml-1"
                    />
                </div>
            }
        </>
    )
}

export { PopinOpener };
