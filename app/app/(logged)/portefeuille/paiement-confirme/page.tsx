"use server"

import { Block } from "@/components/custom/block";
import { Container } from "@/components/custom/container";
import { Button } from "@/components/ui/button";
import { SHOP_CARS_PAGE, WALLET_PAGE } from "@/constants/routing";
import language from "@/messages/fr";
import Image from 'next/image';

export default async function PaymentConfirmation() {

    return(
        <main>
            <Container>
                <h1
                className="h1"
                dangerouslySetInnerHTML={{
                    __html: language.wallet.payment_confirmed.title,
                }}
                ></h1>
            </Container>

            <Container className="mt-6">
                <Block containerClassName="block-animation mb-4 svg-primary text-center" childClassName="p-4">
                    <Image src={`/assets/icons/big/check.svg`} alt="" quality={100} width={80} height={80} className="mb-4 mx-auto" />
                    <p className="text-primary font-bold text-medium mb-4">{language.wallet.payment_confirmed.description}</p>
                    <p>{language.wallet.payment_confirmed.thanks}</p>
                    <Button className="mt-6" variant="secondary" asChild>
                        <a href={WALLET_PAGE}>Retourner au portefeuille</a>
                    </Button>
                    <Button className="mt-4" asChild>
                        <a href={SHOP_CARS_PAGE}>Acheter des cosmétiques</a>
                    </Button>
                </Block>
            </Container>
        </main>
    )
}
