import { getSession } from "@/lib/security";
import { redirect } from "next/navigation";
import { ValidateRegisterForm } from "./_component/ValidateRegisterForm";
import { HOME_PAGE } from "@/constants/routing";
import { BackButton } from "@/app/_components/BackButton";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import Image from "next/image";
import language from "@/messages/fr";

interface ConfirmationInscriptionPageProps {
  params: {
    token: string;
  };
}

async function ConfirmationInscriptionPage({
  params: { token },
}: ConfirmationInscriptionPageProps) {
  const session = await getSession();

  if (session) {
    redirect(HOME_PAGE);
  }

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
        <h1
          className="mt-2 h1"
          dangerouslySetInnerHTML={{ __html: language.registration.validation.title }}
        />
        <Block containerClassName="mt-6" childClassName="w-full p-4">
          <p className="mb-4">{language.registration.validation.description}</p>
          <ValidateRegisterForm token={token} />
        </Block>
      </Container>
    </main>
  );
}

export default ConfirmationInscriptionPage;
