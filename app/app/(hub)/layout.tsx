import { cookies } from "next/headers";
import { Header } from "../_components/Header";
import { Menu } from "../_components/Menu";
import { NotificationPopup, PopupNotificationData } from "@/components/custom/notification-popup";

export default async function Layout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const token = cookies().get("session")?.value;
  let popupNotification: PopupNotificationData | null = null;

  if (token) {
    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/notifications/popup`, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
      cache: "no-store",
    });

    if (res.ok) {
      const data = await res.json();
      popupNotification = Array.isArray(data) && data.length > 0 ? data[0] : null;
    }
  }

  return (
    <>
      <Header isHub={true} />
      {children}
      <Menu />
      {popupNotification && (
        <NotificationPopup notification={popupNotification} />
      )}
    </>
  );
}
