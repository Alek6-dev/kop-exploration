"use server";

import language from "@/messages/fr";
import { cookies } from "next/headers";

export interface SelectBonusDataType {
  entityUuid: string,
  type: 'strategy' | 'duel',
  bonusUuid: string,
  targetUuid: string | null,
}
const selectBonus_action = async (
  data: SelectBonusDataType,
): Promise<string> => {
  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  // console.log("bonus data in action : ", data);

  // If the user is connected, submit the data
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/bonus/select`, {
    method: "POST",
    body: JSON.stringify(data),
    headers: {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    },
  });

  // console.log("res in action : ", res);

  // If the request failed, return an error message
  if (!res.ok) {
    const parsedResponse = await res.json();
    return JSON.stringify({
      status: 0,
      message: parsedResponse['hydra:description'],
    });
  }

  // If the request succeeded, return a success message
  return JSON.stringify({ status: 1, message: language.championship.race.toast.success});
};

export { selectBonus_action };
