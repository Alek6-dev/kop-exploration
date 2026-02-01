"use server";

import { LOGIN_PAGE } from "@/constants/routing";
import { logout } from "@/lib/security";
import { redirect } from "next/navigation";

const logOut_action = async () => {
  await logout();
  redirect(LOGIN_PAGE);
};

export default logOut_action;
