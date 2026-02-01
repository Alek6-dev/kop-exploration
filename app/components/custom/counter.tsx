import { cn } from "@/lib/utils";
import { ReactNode } from "react";

export interface CounterProps {
    counterValue: Number|String,
    className?: String,
}

const Counter = ({ ...props }: CounterProps) => {
    return(
        <div className={cn("flex-centering rounded-md font-bold text-sm min-w-4", props.className)}>{String(props.counterValue)}</div>
    )
}

export { Counter };
