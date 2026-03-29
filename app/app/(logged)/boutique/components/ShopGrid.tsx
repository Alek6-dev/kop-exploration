"use client"

import Image from "next/image";
import { Button } from "@/components/ui/button";
import { shopItemArray } from "../page";
import { Container } from "@/components/custom/container";
import { ShopItem } from "./ShopItem";
import { Popin } from "@/components/custom/popin";
import { useState } from "react";
import language from "@/messages/fr";
import { useToast } from "@/components/ui/use-toast";
import { usePathname, useRouter } from "next/navigation";
import { buyItem_action } from "@/actions/paddock/buyItem-action";
import { MY_PADDOCK_PAGE, WALLET_PAGE } from "@/constants/routing";
import { equipItem_action } from "@/actions/paddock/equipItem-action";
import { A } from "@/components/custom/link";

export interface ShopGridProps {
    shopData: Array<shopItemArray>,
    type?: 'car' | 'helmet',
    credits: number
}

const ShopGrid = ({ ...props }: ShopGridProps) => {
    const { toast } = useToast();
    const router = useRouter();
    const pathname = usePathname();

    const [popinContent, setPopinContent] = useState<shopItemArray | null>(null);
    const handlePopinContent = (newActiveState: shopItemArray) => {
        setPopinContent(newActiveState);
    }

    const buyItem = async (uuid: string) => {

        if(!uuid) return;

        const resString: string = await buyItem_action(uuid);
        const res = JSON.parse(resString);

        if (res.status === 1) {
            toast({
                title: res.message,
            });
            window.location.reload();
            return;
        }

        toast({
            title: res.message,
            variant: "destructive",
        });
    }

    const equipItem = async (uuid: string) => {
        const resString: string = await equipItem_action(uuid);
        const res = JSON.parse(resString);

        if (res.status === 1) {
            toast({
                title: res.message,
            });
            router.push(MY_PADDOCK_PAGE);
            return;
        }

        toast({
            title: res.message,
            variant: "destructive",
        });
    }

    const goToWallet = () => {
        document.body.classList.toggle('touch-none');
        document.body.classList.toggle('overflow-hidden');
        router.push(WALLET_PAGE);
    }

    return (
        <>
            <div className="overflow-hidden">
                <Container className="mt-6 grid grid-cols-3 gap-2 sm:grid-cols-4 sm:gap-4">
                    {props.shopData.map((item: shopItemArray) => (
                        <ShopItem key={item.uuid} item={item} handlePopinContent={handlePopinContent} equipItem={equipItem} popinId="popin" type={props.type} />
                    ))}
                </Container>
            </div>
            <Popin title="" buyPopin={true} id="popin" className="pt-0 px-0">
                <div className={"w-full aspect-[4/3] flex-centering cosmetic-gradient-mask cosmetic-"+props.type} style={{backgroundColor: popinContent?.color}}>
                    <Image src={`${process.env.NEXT_PUBLIC_API_URL}/${props.type === 'car' ? popinContent?.image1 : popinContent?.image2}`} alt="" quality={80} width={400} height={300} className="object-contain w-4/5 max-h-40" />
                </div>
                <div className="pt-6 px-6">
                    <h3 className="h3 mb-2 text-white">{popinContent?.name}</h3>
                    <p>{popinContent?.description}</p>
                    <div className="flex-v-centering flex-wrap justify-between mt-4">
                        {popinContent?.isPossessed?
                            <>
                            {popinContent?.isSelected === false &&
                                <Button onClick={() => equipItem(popinContent?.uuid)}>{language.shop.buttons.equip}</Button>
                            }
                            </>
                        :
                            <>
                                <div className="flex-v-centering">
                                    <Image
                                        src="/assets/icons/money/kop.svg"
                                        alt=""
                                        quality={100}
                                        width={24}
                                        height={24}
                                    />
                                    <span className="ml-2 font-bold text-primary">{popinContent?.price}</span>
                                </div>
                                {popinContent ?
                                    <>
                                        {popinContent.price > -1 && props.credits >= popinContent.price ?
                                            <Button className="flex-initial w-auto" onClick={() => buyItem(popinContent.uuid)}>{language.shop.buttons.confirm_buy}</Button>
                                            :
                                            <>
                                                <span className="text-red font-bold">{language.shop.not_enough_credits}</span>
                                                <div className="mt-2 text-center p-4 bg-white/10 rounded-lg">{language.shop.not_enough_credits_description} <br/><button className="link text-primary appearance-none" onClick={() => goToWallet()}>{language.shop.buy_credits}</button></div>
                                            </>
                                        }
                                    </>
                                    : null
                                }
                            </>
                        }
                    </div>
                </div>
            </Popin>
        </>
    )
}

export { ShopGrid };
