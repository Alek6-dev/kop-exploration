"use server"

import { Block } from "@/components/custom/block";
import { Container } from "@/components/custom/container";
import language from "@/messages/fr";

export default async function DeleteAccountConfirmation() {

    return(
        <main>
            <Container className="mt-10">
                <h1
                className="h1"
                dangerouslySetInnerHTML={{
                    __html: language.profile.confirmation_delete.title,
                }}
                ></h1>
            </Container>

            <Container className="mt-6">
                <Block containerClassName="block-animation mb-4" childClassName="p-4">
                    <p dangerouslySetInnerHTML={{
                    __html: language.profile.confirmation_delete.description,
                }}></p>
                </Block>
            </Container>
        </main>
    )
}
