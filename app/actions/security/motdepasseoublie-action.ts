"use server";

import { MotDePasseOublieFormDataType } from "@/app/(guest)/mot-de-passe-oublie/_components/MotDePasseOublieForm";
import { MotDePasseOublieResultType, motdepasseoublie } from "@/lib/security";

const motdepasseoublie_action = async (formData: MotDePasseOublieFormDataType): Promise<string> => {
  const result: MotDePasseOublieResultType = await motdepasseoublie(formData);
  return JSON.stringify(result);
};

export { motdepasseoublie_action };
