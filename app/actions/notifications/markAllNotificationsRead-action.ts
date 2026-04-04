"use server";

import { cookies } from "next/headers";

const markAllNotificationsRead_action = async (): Promise<void> => {
  const token = cookies().get("session")?.value;
  if (!token) return;

  await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/notifications/read-all`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
  });
};

export { markAllNotificationsRead_action };
