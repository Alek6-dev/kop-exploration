import { redirect } from "next/navigation";
import { getSession } from "@/lib/security";
import { LoginForm } from "./_components/LoginForm";
import Image from "next/image";
import language from "@/messages/fr";
import { Block } from "@/components/custom/block";
import { A } from "@/components/custom/link";
import { Container } from "@/components/custom/container";
import { CGU_PAGE, HOME_PAGE, PRIVACY_PAGE, REGISTER_PAGE } from "@/constants/routing";

export default async function ConnexionScreen() {
  const session = await getSession();

  //   Si l'utilisateur est déjà connecté, on le redirige vers la page d'accueil
  if (session) {
    redirect(HOME_PAGE);
  }

  return (
    <main>
      <picture className="absolute left-0 block w-full overflow-hidden top-24 sm:top-0 login-image login-image-animated">
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
        className="absolute top-0 left-0 block w-full h-82 mix-blend-plus-lighter login-video"
      >
        <source src="/assets/videos/particles.mp4" type="video/mp4" />
      </video>

      <Container className="mt-12 mb-6">
        <Image
          src="/assets/images/logos/kop-white.svg"
          alt="Logo Kop"
          width={131}
          height={153}
          className="block mx-auto login-logo"
        />
        <Block containerClassName="mt-28 sm:mt-48" childClassName="w-full p-4">
          <LoginForm />
        </Block>
      </Container>

      <div className="w-full mt-6 text-center">
        <A href={REGISTER_PAGE} className="text-primary">
          {language.login.registerLink}
        </A>
      </div>

      <div className="text-sm text-center bg-black text-gray login-legals">
        En vous connectant, vous acceptez nos
        <a href={CGU_PAGE} className="font-normal link mx-[3px]">
          conditions générales d’utilisation
        </a>
        ainsi que notre
        <a href={PRIVACY_PAGE} className="font-normal link mx-[3px]">
          politique de confidentialité
        </a>
        .
      </div>
    </main>
  );
}
