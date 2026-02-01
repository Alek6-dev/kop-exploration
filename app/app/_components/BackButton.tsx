"use client";

import React from 'react';
import IconBack from "@/public/assets/icons/back.svg";
import { cn } from "@/lib/utils"

const BackButton = ({ className = "" } : { className?: string}) => {

    const historyBack = () => {
        history.back();
    }

    return (
        <button onClick={() => historyBack()} className={cn("w-6 h-6 flex-centering", className)}>
            <IconBack />
        </button>
    )
}

export { BackButton };
