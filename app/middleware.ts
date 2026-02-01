// @ts-ignore
import type { NextRequest } from "next/server";
import { LOGIN_PAGE } from "./constants/routing";

export function middleware(request: NextRequest) {
  const currentUser = request.cookies.get("session")?.value;

  const restrictedPath = ["/profil", "/boutique", "/mon-paddock", "/championnat", "/portefeuille", "/code-promo", "/quiz"];

  if(!currentUser) {
    for (const path of restrictedPath) {
      if (request.nextUrl.pathname.startsWith(path)) {
        return Response.redirect(new URL(LOGIN_PAGE, request.url));
      }
    }
  }
}

export const config = {
  matcher: ["/((?!api|_next/static|_next/image|.*\\.png$).*)"],
};
