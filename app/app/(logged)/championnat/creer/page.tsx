import { getSession } from "@/lib/security";
import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { CreateChampionshipForm } from "./_components/CreateChampionshipForm";

export default async function CreateChampionship() {
  return (
    <main>
      <Container>
        <h1
          className="h1"
          dangerouslySetInnerHTML={{
            __html: language.championship.create.title,
          }}
        ></h1>
      </Container>

      <Container className="mt-6">
        <Block containerClassName="block-animation" childClassName="p-4">
          <CreateChampionshipForm />
        </Block>
      </Container>
    </main>
  );
}
