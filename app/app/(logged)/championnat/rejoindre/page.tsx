import { getSession } from "@/lib/security";
import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { redirect } from "next/navigation";
import { LOGIN_PAGE } from "@/constants/routing";
import { JoinChampionshipForm } from "./_components/JoinChampionshipForm";

export default async function JoinChampionship() {

  return(
    <main>
      <Container>
        <h1
          className="h1"
          dangerouslySetInnerHTML={{
            __html: language.championship.join.title,
          }}
        ></h1>
      </Container>

      <Container className="mt-6">
        <Block containerClassName="block-animation mb-4" childClassName="p-4">
          <p className="mb-4 text-gray">{language.championship.join.description}</p>
          <JoinChampionshipForm />
        </Block>
      </Container>
    </main>
  )
}
