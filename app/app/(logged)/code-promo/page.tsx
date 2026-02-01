import { getSession } from "@/lib/security";
import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { PromoCodeForm } from "./components/PromoCodeForm";

export default async function PromoCode() {

  return(
    <main>
      <Container>
        <h1
          className="h1"
          dangerouslySetInnerHTML={{
            __html: language.promocode.title,
          }}
        ></h1>
      </Container>

      <PromoCodeForm />
    </main>
  )
}
