"use server";

import { RegisterResultType, register } from "@/lib/security";

const register_action = async (formData: FormData): Promise<string> => {
  const result: RegisterResultType = await register(formData);
  return JSON.stringify(result);
};

export { register_action };
