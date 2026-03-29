"use server";

import language from "@/messages/fr";
import { cookies } from "next/headers";

interface AddBidsDataType {
  driver1uuid?: string|null,
  driver1BidAmount?: number|null,
  driver2uuid?: string|null,
  driver2BidAmount?: number|null,
  teamuuid?: string|null,
  teamBidAmount?: number|null,
}

const addBids_action = async (
  data: AddBidsDataType, uuid: string
): Promise<string> => {
  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  // console.log("data in action : ", data);
  // console.log("uuid in action : ", uuid);

  // If the user is connected, register the bids
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/bid`, {
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
      message: parsedResponse['hydra:description'],
    });
  }

  // If the request succeeded, return a success message
  return JSON.stringify({ status: 1, message: language.championship.sillyseason.toast.success});
};

export { addBids_action };
