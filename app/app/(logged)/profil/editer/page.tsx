import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { EditProfileForm } from "@/app/(logged)/profil/editer/_components/EditProfileForm";

export default async function EditProfile() {
  return (
    <main>
      <Container>
        <h1
          className="h1"
          dangerouslySetInnerHTML={{
            __html: language.profile.edit.title,
          }}
        ></h1>
      </Container>

      <Container className="mt-6">
        <Block containerClassName="block-animation" childClassName="p-4">
            <EditProfileForm />
        </Block>
      </Container>
    </main>
  );
}
