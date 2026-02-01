import Image from 'next/image';
import language from "@/messages/fr";
import { Block } from "@/components/custom/block";
import { BackButton } from "@/app/_components/BackButton";
import { Container } from "@/components/custom/container";
import { MotDePasseOublieForm } from './_components/MotDePasseOublieForm';

export default function MotDePasseOublieScreen() {
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
      <video autoPlay loop muted playsInline className="absolute left-0 block w-full h-30 -top-8 mix-blend-plus-lighter login-video">
          <source src="/assets/videos/particles.mp4" type="video/mp4" />
      </video>

      <Container className="mt-20 mb-6">
        <BackButton className="-ml-2" />
        <h1 className="mt-2 h1" dangerouslySetInnerHTML={{__html: language.forgot_password_request.title}} />
        <Block containerClassName="mt-6" childClassName="w-full p-4">
          <p className="mb-4" dangerouslySetInnerHTML={{__html: language.forgot_password_request.description}} />
          <MotDePasseOublieForm />
        </Block>
      </Container>
    </main>
  );
}
