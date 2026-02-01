
import { StrategyCard } from "@/components/custom/strategyCard";
import { Block } from "@/components/custom/block";
import { Container } from "@/components/custom/container";
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";

export default function NotFound() {
    return(
        <main>
            <Container className="mt-10">
                <h1
                className="h1"
                dangerouslySetInnerHTML={{
                    __html: language.error.page_not_found.title,
                }}
                ></h1>
            </Container>

            <Container className="mt-6">
                <Block containerClassName="block-animation mb-4" childClassName="p-4">
                    <p>{language.error.page_not_found.description}</p>
                    <Button className="mt-4" asChild={true}>
                        <a href="/">{language.error.page_not_found.button}</a>
                    </Button>
                </Block>
            </Container>
        </main>
    )
}
