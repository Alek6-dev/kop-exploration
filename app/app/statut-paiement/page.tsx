"use client"

import { Block } from "@/components/custom/block";
import { Container } from "@/components/custom/container";
import { Button } from "@/components/ui/button";
import { SHOP_CARS_PAGE, WALLET_PAGE } from "@/constants/routing";
import language from "@/messages/fr";
import Image from 'next/image';
import Link from "next/link";
import { useSearchParams } from 'next/navigation'

export default function PaymentStatus() {
    const searchParams = useSearchParams();
    const successParam = searchParams.get('success');

    return(
        <main>
            <Container className="mt-6">
                {successParam === "1" ?
                    <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: language.wallet.payment_confirmed.title,
                    }}></h1>
                :
                    <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: language.wallet.payment_cancelled.title,
                    }}></h1>
                }
            </Container>

            <Container className="mt-6">
                <Block containerClassName="block-animation mb-4 svg-primary text-center" childClassName="p-4">
                    {successParam === "1" ?
                        <>
                            <Image src={`/assets/icons/big/check.svg`} alt="" quality={100} width={80} height={80} className="mb-4 mx-auto" />
                            <p className="text-primary font-bold text-medium mb-4">{language.wallet.payment_confirmed.description}</p>
                            <p>{language.wallet.payment_confirmed.thanks}</p>
                            <Button className="mt-6" variant="secondary" asChild>
                                <Link href={WALLET_PAGE}>{language.wallet.payment_confirmed.cta.back_to_wallet}</Link>
                            </Button>
                            <Button className="mt-4" asChild>
                                <Link href={SHOP_CARS_PAGE}>{language.wallet.payment_confirmed.cta.back_to_shop}</Link>
                            </Button>
                        </>
                    :
                        <>
                            <p className="text-primary font-bold text-medium mb-4">{language.wallet.payment_cancelled.description}</p>
                            <p>{language.wallet.payment_cancelled.retry}</p>
                            <Button className="mt-6" asChild>
                                <Link href={WALLET_PAGE}>{language.wallet.payment_confirmed.cta.back_to_wallet}</Link>
                            </Button>
                        </>
                    }
                </Block>
            </Container>
        </main>
    )
}
