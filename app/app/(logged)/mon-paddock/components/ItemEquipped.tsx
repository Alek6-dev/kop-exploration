"use client"

import Image from "next/image";
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";

export interface ItemEquippedProps {
    image: string,
    name: string,
    type: "car"|"helmet",
}

const ItemEquipped = ({ ...props }: ItemEquippedProps) => {
    const typeText = props.type === "car" ? language.mypaddock.car : language.mypaddock.helmet;
    const link = props.type === "car" ? "/boutique?source=paddock" : "/boutique/casques?source=paddock";

    return(
        <div className="flex flex-col items-center px-4 relative">
            <div className="w-full h-12 flex-centering">
                <Image
                    src={`${process.env.NEXT_PUBLIC_API_URL + '/' + props.image}`}
                    alt=""
                    quality={80}
                    width="0"
                    height="0"
                    className="relative block w-full max-w-25 h-12 object-contain"
                />
            </div>
            <h4 className="h4 mt-1">{typeText}</h4>
            <p className="text-sm text-gray mb-3 mt-[2px]">{props.name}</p>
            <Button size="sm" variant="light" className="w-auto" asChild>
                <a href={link}>{language.mypaddock.buttons.change}</a>
            </Button>
        </div>
    )
}

export { ItemEquipped };
