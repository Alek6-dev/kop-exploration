import { cookies } from "next/headers";
import { Container } from "@/components/custom/container";
import { NotificationItem, NotificationData } from "./_components/NotificationItem";
import { MarkAllReadButton } from "./_components/MarkAllReadButton";

export default async function NotificationsPage() {
  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `Bearer ${token}`,
    "Content-Type": "application/json",
  };

  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/notifications`, {
    headers,
    cache: "no-store",
  });

  const notifications: NotificationData[] = res.ok ? await res.json() : [];
  const hasUnread = notifications.some((n) => !n.isRead);

  return (
    <main>
      <Container>
        <h1 className="h1">
          Mes <span>notifications</span>
        </h1>
      </Container>

      <Container className="mt-6">
        {notifications.length === 0 ? (
          <p className="text-gray text-sm">Aucune notification pour le moment.</p>
        ) : (
          <>
            {hasUnread && (
              <div className="mb-4 flex justify-end">
                <MarkAllReadButton />
              </div>
            )}
            {notifications.map((notification) => (
              <NotificationItem key={notification.uuid} notification={notification} />
            ))}
          </>
        )}
      </Container>
    </main>
  );
}
