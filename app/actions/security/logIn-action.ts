"use server";

import { LoginFormDataType } from "@/app/(guest)/connexion/_components/LoginForm";
import { LoginResultType, login } from "@/lib/security";

const logIn_action = async (formData: LoginFormDataType): Promise<string> => {
  const result: LoginResultType = await login(formData);
  return JSON.stringify(result);
};

export { logIn_action };
