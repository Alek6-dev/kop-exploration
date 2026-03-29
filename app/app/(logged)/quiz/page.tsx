import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { Button } from "@/components/ui/button";
import Image from "next/image";
import Link from "next/link";

export default async function Profile() {

  return (
    <main>
      <Container>
        <h1
          className="h1"
          dangerouslySetInnerHTML={{
            __html: language.quiz.title,
          }}
        ></h1>
      </Container>

      <Container className="mt-6">
        <Block containerClassName="block-animation mt-4" childClassName="p-4">
            <p>{language.quiz.description}</p>
        </Block>
        <Block containerClassName="block-animation mt-4" childClassName="px-4 pt-4">
            <p className="mb-4">{language.quiz.crowdfunding1}</p>
            <p className="mb-4">{language.quiz.crowdfunding2}</p>
            <p className="mb-4">{language.quiz.crowdfunding3}</p>
            <Button asChild>
              <Link href="https://www.leetchi.com/fr/c/developpement-de-lapplication-king-of-paddock-1849271?utm_source=copylink&utm_medium=social_sharing" target="_blank">
              {language.quiz.cta}
              </Link>
            </Button>
            <Image
              src="/assets/images/quiz/quiz.png"
              alt="Quiz"
              quality={100}
              width={300}
              height={263}
              className="relative block w-full mx-auto mt-6"
            />
        </Block>
      </Container>
    </main>
  );
}
