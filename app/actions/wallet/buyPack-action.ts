"use server";

import { PAYMENT_CONFIRMATION_PAGE } from "@/constants/routing";
import language from "@/messages/fr";
import { cookies } from "next/headers";

interface BuyPackDataType {
    creditPackUuid: string,
    cgu: boolean,
    retractation: boolean,
}

const BuyPack_action = async (
  data: BuyPackDataType
): Promise<string> => {

  // If the user is not connected, return an error message
  const token = cookies().get("session")?.value;

  if (!token) {
    return JSON.stringify({
      status: 0,
      message: language.error.not_logged,
    });
  }

  const callbackURL = process.env.NEXT_PUBLIC_URL+PAYMENT_CONFIRMATION_PAGE;

  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/payment/checkout?credit_pack_id=${data.creditPackUuid}&url_callback=${callbackURL}`, {
    method: "GET",
    headers: {
      Authorization: `${"Bearer " + token}`,
      "Content-Type": "application/json",
    },
  });

  const paymentURL = await res.json();

  // If the request failed, return an error message
  if (!res.ok) {
    const parsedResponse = await res.json();
    return JSON.stringify({
      status: 0,
      message: parsedResponse['hydra:description'],
    });
  }

  // If the request succeeded, redirect to payment page
  return JSON.stringify({ status: 1, message: language.wallet.toast.redirect_payment_page, paymentURL: paymentURL.urlCallback});
};

export { BuyPack_action };
