"use server";

import { cookies } from "next/headers";

const deleteNotification_action = async (uuid: string): Promise<void> => {
  const token = cookies().get("session")?.value;
  if (!token) return;

  await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/notifications/${uuid}`, {
    method: "DELETE",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
  });
};

export { deleteNotification_action };
