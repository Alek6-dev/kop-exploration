import React from "react";
import { BackButton } from "@/app/_components/BackButton";
import { Container } from "@/components/custom/container";
import Image from "next/image";
import { Avatar, AvatarImage } from "@/components/ui/avatar";
import language from "@/messages/fr";
import { getSession } from "@/lib/security";
import { LOGIN_PAGE, PROFILE_PAGE, WALLET_PAGE } from "@/constants/routing";
import { cookies } from "next/headers";
import { NotificationBadge } from "@/components/custom/notification-badge";
import { redirect } from "next/navigation";

async function Header({isHub = false} : {isHub : boolean}) {
  const session = await getSession();
  if(session) {
    let avatarFile;

    if (session?.avatar_url && session?.avatar_url !== "uploads/images/avatar/") {
      avatarFile = `${process.env.NEXT_PUBLIC_API_URL}/${session?.avatar_url}`;
    } else {
      avatarFile = "/assets/images/avatar/avatar-generic@2x.jpg"
    }

    const token = cookies().get("session")?.value;
    const headers = {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    }
    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${session?.id}`, { headers });
    const userData = await res.json();

    if (!res.ok) {
      redirect(LOGIN_PAGE);
    }

    const resNotifications = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/notifications`, {
      headers,
      cache: "no-store",
    });
    const notifications = resNotifications.ok ? await resNotifications.json() : [];
    const hasUnreadNotifications = Array.isArray(notifications) && notifications.some((n: { isRead: boolean }) => !n.isRead);

    return (
      <header className="bg-black">
        <Container className="justify-between py-2 pl-2 flex-v-centering">
          {isHub ? (
            <div className="ml-2 font-bold">
              {language.hub.welcome} <span>{session?.pseudo}</span>
            </div>
          ) : (
            <BackButton />
          )}
          <div className="header-avatar-wallet flex-v-centering">
            <a href={WALLET_PAGE} className="flex-v-centering">
              <span className="mr-1 text-sm text-primary">{userData.creditWallet?.credit ?? 0}</span>
              <Image
                src="/assets/icons/money/kop.svg"
                alt=""
                quality={100}
                width={24}
                height={24}
              />
            </a>
            <a href={PROFILE_PAGE} className="block ml-2">
              <NotificationBadge hasUnread={hasUnreadNotifications}>
                <Avatar className="h-[36px] w-[36px] block rounded-full">
                  <AvatarImage
                    src={avatarFile}
                  />
                </Avatar>
              </NotificationBadge>
            </a>
          </div>
        </Container>
      </header>
    );
  }
  else {
    return (
      <header className="bg-black">
        <Container className="justify-between py-2 pl-2 flex-v-centering">
          <BackButton />
        </Container>
      </header>
    );
  }
}

export { Header };
