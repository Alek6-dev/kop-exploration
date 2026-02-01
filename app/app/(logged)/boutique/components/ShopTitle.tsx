"use client"

import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { TabsAsPage } from "@/components/custom/tabsAsPage";
import { A } from "@/components/custom/link";
import { PROMO_CODE_PAGE, SHOP_CARS_PAGE, SHOP_HELMETS_PAGE } from "@/constants/routing";
import { usePathname, useSearchParams } from "next/navigation";

export interface ShopTitleProps {
    title: any,
}

const ShopTitle = ({ ...props }: ShopTitleProps) => {
    const searchParams = useSearchParams();
    const source = searchParams.get('source');
    const pathname = usePathname();

    let title = props.title;
    if(source === "paddock") {
        title = pathname === '/boutique' ? language.shop.title_car : language.shop.title_helmet;
    }

    const tabs = [
        {
            label: language.shop.tabs.cars,
            url: SHOP_CARS_PAGE,
            id: "tab-cars",
        },
        {
            label: language.shop.tabs.helmets,
            url: SHOP_HELMETS_PAGE,
            id: "tab-helmets",
        },
    ]

    return(
        <>
            <Container>
                <h1
                className="h1 mb-1"
                dangerouslySetInnerHTML={{
                    __html: title,
                }}
                ></h1>
                {/* {!source &&
                    <A href={PROMO_CODE_PAGE} className="w-auto">Utiliser un code promo</A>
                } */}
        </Container>
        {!source &&
            <TabsAsPage tabs={tabs} defaultActive={tabs[0].id} className="mt-2 sticky top-0 z-10 bg-black no-delay" />
        }
      </>
    )
}

export { ShopTitle };
