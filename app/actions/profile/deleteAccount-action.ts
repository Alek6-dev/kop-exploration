"use server";

import { logout } from "@/lib/security";
import language from "@/messages/fr";
import { cookies } from "next/headers";

const deleteAccount_action = async (
  uuid: string
): Promise<string> => {
  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  // console.log("uuid in action : ", uuid);

  // If the user is connected, proceed and delete account
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/delete/${uuid}`, {
    method: "POST",
    headers: {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    },
  });

  // If the request failed, return an error message
  if (!res.ok) {
    const parsedResponse = await res.json();
    return JSON.stringify({
      status: 0,
      message: parsedResponse['hydra:description'],
    });
  }

  // If the request succeeded, logout and return status ok
  return JSON.stringify({ status: 1, message: ""});
};

export { deleteAccount_action };
