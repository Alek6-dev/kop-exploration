import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { getSession } from "@/lib/security";
import { DeleteAccountButton } from "./components/DeleteAccountButton";

export default async function DeleteProfile() {

  const session = await getSession();
  const uuid = session ? session.id : "";

  return (
    <main>
      <Container>
        <h1
          className="h1"
          dangerouslySetInnerHTML={{
            __html: language.profile.delete.title,
          }}
        ></h1>
      </Container>

      <Container className="mt-6">
        <Block containerClassName="block-animation" childClassName="px-4 py-3">
          <p dangerouslySetInnerHTML={{__html: language.profile.delete.description}}></p>
          {uuid !== "" &&
            <DeleteAccountButton uuid={uuid} />
          }
        </Block>
      </Container>
    </main>
  );
}
