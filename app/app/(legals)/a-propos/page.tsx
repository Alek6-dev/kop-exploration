import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { LinkBlock } from "@/components/custom/linkBlock";
import { CGU_PAGE, CGV_PAGE, LEGAL_MENTIONS_PAGE, PRIVACY_PAGE } from "@/constants/routing";

export default function About() {
    return (
        <main>
            <Container>
                <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: language.about.title,
                    }}
                ></h1>
            </Container>

            <Container className="mt-6">
                <LinkBlock title={language.about.cgu.title} url={CGU_PAGE} />
                <LinkBlock title={language.about.cgv.title} url={CGV_PAGE} />
                <LinkBlock title={language.about.privacy.title} url={PRIVACY_PAGE} />
                <LinkBlock title={language.about.legal.title} url={LEGAL_MENTIONS_PAGE} />
            </Container>
        </main>
    );
}
