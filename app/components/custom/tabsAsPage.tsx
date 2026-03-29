"use client";

import React, { ReactNode, useState } from 'react';
import { cn } from "@/lib/utils"
import { A } from './link';
import { usePathname } from 'next/navigation';
import { Counter } from './counter';
import { Button } from '../ui/button';

export interface TabsProps {
    tabs: object[],
    defaultActive: String,
    className ?: String,
}

const TabsAsPage = ({ ...props }: TabsProps ) => {
    const pathname = usePathname();

    return (
        <nav className={cn("flex w-full", props.className)}>
        {props.tabs.map((tab: any) => (
            <a href={tab.url} key={tab.id} id={tab.id} className={cn("flex-1 text-center flex-centering p-2 font-bold border-b bg-none " + (pathname === tab.url ? "border-primary text-primary [&>div]:bg-primary" : "border-white-6 text-gray"), tab.ClassName)} title={tab.label}>
                {tab.label}
                {tab.counter != null &&
                    <Counter className="h-[14px] px-1 bg-gray text-black ml-2" counterValue={tab.counter} />
                }
            </a>
        ))}
        </nav>
    )
}

export { TabsAsPage };
