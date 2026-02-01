"use server";

import { JoinChampionshipFormDataType } from "@/app/(logged)/championnat/rejoindre/_components/JoinChampionshipForm";
import language from "@/messages/fr";
import { cookies } from "next/headers";

const joinChampionship_action = async (
  formData: JoinChampionshipFormDataType
): Promise<string> => {
  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }
  const player = {
    playerName: formData.playerName,
  }

  // If the user is connected, create the championship
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/join/${formData.code}`, {
    method: "POST",
    body: JSON.stringify(player),
    headers: {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    },
  });

  const parsedResponse = await res.json();

  // If the request failed, return an error message
  if (!res.ok) {
    return JSON.stringify({
      status: 0,
      message: parsedResponse.message,
    });
  }

  // If the request succeeded, return a success message
  return JSON.stringify({ status: 1, message: language.championship.join.toast.success, uuid: parsedResponse.uuid });
};

export { joinChampionship_action };
