import { ReactHTMLElement, ReactNode } from "react";
import { cn } from "@/lib/utils"

export interface ContainerProps {
    children: ReactNode,
    className?: String,
}

const Container = ({ children, ...props }: ContainerProps ) => {
    return (
        <div className={cn("container relative", props.className)}>
            {children}
        </div>
    )
}

export { Container };
