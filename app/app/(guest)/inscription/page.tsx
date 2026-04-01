import { RegisterForm } from "./_components/RegisterForm";
import Image from "next/image";
import language from "@/messages/fr";
import { Block } from "@/components/custom/block";
import { BackButton } from "@/app/_components/BackButton";
import { Container } from "@/components/custom/container";
import { cookies } from "next/headers";
import { CGU_PAGE, PRIVACY_PAGE } from "@/constants/routing";

export default async function InscriptionScreen() {
  const resParameters = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/parameters`);
  const parameters = await resParameters.json();

  if (!resParameters.ok) {
    throw new Error(parameters.message);
  }

  const userConfirmationByAdmin:number = parameters.find((el: { code: string; }) => el.code === "user_confirmation_by_admin")?.["value"] ?? 0;

  return (
    <main>
      <picture className="absolute left-0 w-full -top-16 sm:-top-40 login-image">
        <Image
          src="/assets/images/login/login@2x.jpg"
          alt="KOP Banner"
          quality={100}
          width={400}
          height={285}
          className="relative block w-full"
          sizes="100vw"
        />
      </picture>
      <video
        autoPlay
        loop
        muted
        playsInline
        className="absolute left-0 block w-full h-30 -top-8 mix-blend-plus-lighter login-video"
      >
        <source src="/assets/videos/particles.mp4" type="video/mp4" />
      </video>

      <Container className="mt-20 mb-6">
        <BackButton className="-ml-2" />
        <h1
          className="mt-2 h1"
          dangerouslySetInnerHTML={{ __html: language.registration.title }}
        />
        <Block containerClassName="mt-6" childClassName="w-full p-4">
          <RegisterForm userConfirmationByAdmin={userConfirmationByAdmin} />
        </Block>

        <div className="text-sm text-center bg-black text-gray mt-6">
          En vous inscrivant, vous acceptez nos
          <a href={CGU_PAGE} className="font-normal link mx-[3px]">
            conditions générales d’utilisation
          </a>
          ainsi que notre
          <a href={PRIVACY_PAGE} className="font-normal link mx-[3px]">
            politique de confidentialité
          </a>
          .
        </div>
      </Container>

    </main>
  );
}
