"use server";

import { cookies } from "next/headers";

const registerValidation_action = async (token: string): Promise<0 | 1> => {
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/validation/${token}`, {
    method: "POST",
    body: "",
    headers: { "Content-Type": "application/ld+json" },
  });

  if (res.status === 201) {
    const parsedResponse = await res.json();
    cookies().set("session", parsedResponse.token);
    return 1;
  }
  return 0;
};

export { registerValidation_action };
