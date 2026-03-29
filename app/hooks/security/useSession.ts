// Need this for acccess session on client side
// const session = useSession(); => session is null if not connected and is an object with user data if connected

import { decodeJwt } from "jose";
import Cookies from "js-cookie";

const useSession = () => {
  const sessionCookie = Cookies.get("session");

  if (!sessionCookie) return null;

  return decodeJwt(sessionCookie) as {pseudo: string, username: string, avatar_url: string|null, email: string, id: string, roles: string[], exp: number, iat:number};
};

export { useSession };
