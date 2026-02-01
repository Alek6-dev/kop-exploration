import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";

export default function About() {
    return (
        <main>
            <Container>
                <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: language.legal.title,
                    }}
                ></h1>
            </Container>

            <Container className="mt-6">
                <Block childClassName="p-4 pt-2 block-cms">
                    <h2>Fantasy King</h2>
                    <p><b>Siège social :</b><br/> La Loco’Working, 4 impasse des frères lumière, 38300 BOURGOIN JALLIEU</p>
                    <p><b>Forme juridique :</b><br/> Société par Actions Simplifée</p>
                    <p><b>Capital social :</b><br/> 5000 €</p>
                    <p><b>Numéro d&#39;identification SIREN :</b><br/> 948 716 915</p>
                    <p><b>Numéro de TVA :</b><br/> FR37948716915</p>
                    <p><b>Email :</b><br/> contact@king-of-paddock.com</p>
                    <p><b>Numéro de téléphone :</b><br/> +33 (0)7 44 88 63 97</p>
                    <p><b>Directeur de la publication :</b><br/> M. Alexis Bissuel</p>
                    <p><b>Autorité compétente :</b><br/> N/A</p>
                    <p><b>Hébergeur :</b><br/> OVH</p>
                    <p><b>Siège social de l’hébergeur :</b><br/> 2 rue Kellermann, <br/>59100 Roubaix</p>
                    <p><b>Contact de l’hébergeur :</b><br/> +33 9 72 10 10 07</p>
                </Block>
            </Container>
        </main>
    );
}
