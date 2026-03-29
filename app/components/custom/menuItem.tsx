"use client"

import { cn } from "@/lib/utils";
import { usePathname } from "next/navigation";
import { ReactNode } from "react";

export interface MenuItemProps {
    children: ReactNode,
    href: string,
    title: string,
    className?: string,
}

const MenuItem = ({ children, ...props }: MenuItemProps) => {
    const pathName = usePathname();
    const category = "/"+pathName.split("/")[1];
    const isActive = category === props.href ? "is-active" : "";

    return (
        <a href={props.href} className={cn("menu-item", props.className, isActive)}>
            {children}
            <span>{props.title}</span>
        </a>
    )
}

export { MenuItem };
