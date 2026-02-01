"use server";

import language from "@/messages/fr";
import { cookies } from "next/headers";

const cancelChampionship_action = async (
  championshipUuid: String
): Promise<string> => {
  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  // If the user is connected, cancel the championship
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/cancel/${championshipUuid}`, {
    method: "POST",
    headers: {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    },
  });

  // If the request failed, return an error message
  if (!res.ok) {
    return JSON.stringify({
      status: 0,
      message: language.championship.invitation.toast.cancel.error,
    });
  }

  // If the request succeeded, return a success message
  return JSON.stringify({ status: 1, message: language.championship.invitation.toast.cancel.success });
};

export { cancelChampionship_action };
