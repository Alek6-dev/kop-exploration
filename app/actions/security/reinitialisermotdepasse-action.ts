"use server";

import { ReinitialiserMotDePasseFormDataType} from "@/app/(guest)/mot-de-passe-oublie/[token]/_components/ReinitialiserMotDePasseForm";
import { ReinitialiserMotDePasseResultType, reinitialisermotdepasse } from "@/lib/security";

const reinitialisermotdepasse_action = async (formData: ReinitialiserMotDePasseFormDataType, token: string): Promise<string> => {
  const result: ReinitialiserMotDePasseResultType = await reinitialisermotdepasse(formData, token);
  return JSON.stringify(result);
};

export { reinitialisermotdepasse_action };
