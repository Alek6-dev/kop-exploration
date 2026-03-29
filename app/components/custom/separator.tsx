//import * as React from "react"
import { cn } from "@/lib/utils";

export interface SeparatorProps {
    className?: String,
}

const Separator = ({ className }: SeparatorProps) => {
    return(
        <div className={cn("separator", className)}></div>
    )
}

export { Separator };
