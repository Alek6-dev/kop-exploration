"use server";

import language from "@/messages/fr";
import { cookies } from "next/headers";

const startChampionship_action = async (
    uuid: string)
: Promise<string> => {

  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;
  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  // If the user is connected, start the championship
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/start/${uuid}`, {
    method: "POST",
    headers: {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    },
  });

  // If the request failed, return an error message
  if (!res.ok) {
    const parsedResponse = await res.json();
    // console.log(parsedResponse);
    return JSON.stringify({
      status: 0,
      message: parsedResponse['hydra:description'],
    });
  }

  // If the request succeeded, return a success message
  return JSON.stringify({ status: 1, message: language.championship.invitation.toast.start.success});
};

export { startChampionship_action };
