"use server";

import { cookies } from "next/headers";

const markNotificationRead_action = async (uuid: string): Promise<void> => {
  const token = cookies().get("session")?.value;
  if (!token) return;

  await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/notifications/${uuid}/read`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
  });
};

export { markNotificationRead_action };
