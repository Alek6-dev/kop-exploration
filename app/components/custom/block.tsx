import { ReactHTMLElement, ReactNode } from "react";
import { cn } from "@/lib/utils"

export interface BlockProps {
    children: ReactNode,
    containerClassName?: String,
    childClassName?: String,
    onClick?: (e: any) => void,
}

const Block = ({ children, ...props }: BlockProps ) => {
    return (
        <div className={cn("w-full zone", props.containerClassName)}>
            <div className={cn("relative flex flex-col", props.childClassName)}>
                {children}
            </div>
        </div>
    )
}

export { Block };
