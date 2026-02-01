"use server";

import { EditProfileResultType, editprofile } from "@/lib/security";

const editProfile_action = async (data: (any)[]): Promise<string> => {
  const result: EditProfileResultType = await editprofile(data);
  return JSON.stringify(result);
};

export { editProfile_action };
