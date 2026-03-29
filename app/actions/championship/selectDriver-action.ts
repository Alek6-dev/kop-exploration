"use server";

import { SelectDriverFormDataType } from "@/app/(logged)/championnat/[uuid]/(active)/course/components/SelectDriverForm";
import language from "@/messages/fr";
import { cookies } from "next/headers";

const selectDriver_action = async (
  data: SelectDriverFormDataType,
  uuid: string,
  type: string,
): Promise<string> => {
  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  const typePath = type === "gp" ? "strategy" : "duel";

  // If the user is connected, submit the data
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/${typePath}/select-driver`, {
    method: "POST",
    body: JSON.stringify(data),
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
      message: parsedResponse.message,
    });
  }

  // If the request succeeded, return a success message
  return JSON.stringify({ status: 1, message: language.championship.race.toast.success});
};

export { selectDriver_action };
