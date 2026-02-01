"use client";

import React, { ReactNode, useState } from 'react';
import { cn } from "@/lib/utils"
import { A } from './link';
import { usePathname } from 'next/navigation';
import { Counter } from './counter';
import { Button } from '../ui/button';

export interface TabsProps {
    changeState: any,
    tabs: object[],
    defaultActive: String,
    className ?: String,
}

const Tabs = ({ ...props }: TabsProps ) => {
    const pathname = usePathname();
    const [state, setState] = useState(props.defaultActive);
    const handleClick = (event: any) => {
        let value = event.target.id;
        setState(value);
        props.changeState(value);
    };

    return (
        <nav className={cn("flex w-full", props.className)}>
        {props.tabs.map((tab: any) => (
            <button onClick={handleClick} key={tab.id} id={tab.id} className={cn("flex-1 text-center flex-centering p-2 font-bold border-b bg-none " + (state === tab.id ? "tab-active border-primary text-primary [&>div]:bg-primary" : "border-white-6 text-gray"), tab.ClassName)} title={tab.label}>
                {tab.label}
                {tab.counter != null &&
                    <Counter className="h-[14px] px-1 bg-gray text-black ml-2" counterValue={tab.counter} />
                }
            </button>
        ))}
        </nav>
    )
}

export { Tabs };
