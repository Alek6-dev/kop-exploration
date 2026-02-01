"use client"

import Image from "next/image";
import { Button } from "@/components/ui/button";
import { Block } from "@/components/custom/block";
import { shopItemArray } from "../page";
import { useState } from "react";
import IconCheck from "@/public/assets/icons/small/check.svg";
import language from "@/messages/fr";

export interface ShopItemProps {
    item: shopItemArray,
    handlePopinContent: any,
    equipItem: any,
    popinId: string,
    type?: 'car' | 'helmet',
}

const ShopItem = ({ ...props }: ShopItemProps) => {

    //const [content, setContent] = useState<shopItemArray | null>(null);

    const setPopinContent = (item:shopItemArray) => {
        //setContent(item);
        props.handlePopinContent(item);
        document.getElementById(props.popinId)?.classList.add("!translate-y-0");
        // Avoid scrolling and interaction of body when popin is open
        document.body.classList.toggle('touch-none');
        document.body.classList.toggle('overflow-hidden');
    }

    //console.log(props.type)

    const cosmeticImage = props.type === 'car' ? props.item.image1 : props.item.image2;

    //console.log(cosmeticImage);

    return(
        <>
            {props.item.isPossessed ?
                <div className="block-animation p-2 w-full relative flex flex-col rounded-lg cosmetic-gradient-mask justify-end" style={{backgroundColor: props.item.color}}>
                    <Image src={`${process.env.NEXT_PUBLIC_API_URL + '/' + cosmeticImage}`} alt="" quality={80} width={100} height={60} className="object-contain mt-2 w-full aspect-[10/6]" style={{ color: undefined }} onClick={() => setPopinContent(props.item)} />
                    {props.item.isSelected ?
                        <div className="mt-2 h-6 flex-centering font-bold text-sm text-black">
                            <span className="mr-1">{language.shop.buttons.equipped}</span>
                            <IconCheck />
                        </div>
                    :
                        <Button className="mt-2 text-sm text-black border-black/10" size="xs" variant="light" onClick={() => props.equipItem(props.item.uuid)}>{language.shop.buttons.equip}</Button>
                    }
                </div>
            :
                <button onClick={() => setPopinContent(props.item)} className="block-animation">
                    <Block containerClassName="p-2 justify-end">
                        <div className="flex-v-centering">
                            <Image src="/assets/icons/money/kop.svg" alt="" quality={100} width={16} height={16} />
                            <span className="ml-1 text-sm font-bold text-primary">{props.item.price}</span>
                        </div>
                        <Image src={`${process.env.NEXT_PUBLIC_API_URL + '/' + cosmeticImage}`} alt="" quality={80} width={100} height={60} className="object-contain mt-2 w-full aspect-[10/6]" />
                        <Button className="mt-2 text-sm" size="xs" asChild><span>{language.shop.buttons.buy}</span></Button>
                    </Block>
                </button>
            }
        </>
    )
}

export { ShopItem };
