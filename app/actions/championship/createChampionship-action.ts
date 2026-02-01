"use server";

import { CreateChampionshipFormDataType } from "@/app/(logged)/championnat/creer/_components/CreateChampionshipForm";
import language from "@/messages/fr";
import { cookies } from "next/headers";

interface CreateChampionshipSubmitDataType {
  name: string;
  championshipNumberRace: number;
  championshipNumberPlayer: number;
  jokerEnabled: boolean;
  playerName: string;
}

const createChampionship_action = async (
  formData: CreateChampionshipFormDataType
): Promise<string> => {
  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  const data: CreateChampionshipSubmitDataType = {
    name: formData.name,
    championshipNumberRace: Number(formData.championshipNumberRace),
    championshipNumberPlayer: Number(formData.championshipNumberPlayer),
    jokerEnabled: Boolean(formData.jokerEnabled),
    playerName: formData.playerName,
  };

  // console.log("data post", data);

  // If the user is connected, create the championship
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships`, {
    method: "POST",
    body: JSON.stringify(data),
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
  return JSON.stringify({ status: 1, message: language.championship.create.toast.success, uuid: parsedResponse.uuid });
};

export { createChampionship_action };
