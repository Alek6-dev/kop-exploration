import { LoginFormDataType } from "@/app/(guest)/connexion/_components/LoginForm";
import { decodeJwt } from "jose";
import { cookies } from "next/headers";
import { MotDePasseOublieFormDataType } from "@/app/(guest)/mot-de-passe-oublie/_components/MotDePasseOublieForm";
import { ReinitialiserMotDePasseFormDataType } from "@/app/(guest)/mot-de-passe-oublie/[token]/_components/ReinitialiserMotDePasseForm";
import language from "@/messages/fr";

export interface LoginResultType {
  status: 0 | 1;
  message: string | null;
}

export async function login(
  formData: LoginFormDataType
): Promise<LoginResultType> {
  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/auth`, {
      method: "POST",
      body: JSON.stringify(formData),
      headers: { "Content-Type": "application/json" },
    });

    const parsedResponse = await res.json();
    if (!res.ok) {
      throw new Error(parsedResponse.message);
    }

    cookies().set("session", parsedResponse.token);
    return { status: 1, message: null };
  } catch (e: any) {
    return { status: 0, message: e.message };
  }
}

export interface RegisterResultType {
  status: 0 | 1;
  message: string | null;
}

export async function register(
  formData: FormData
): Promise<RegisterResultType> {
  //console.log(formData);
  try {
    //console.log("formData : ", formData);
    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users`, {
      method: "POST",
      body: formData,
    });

    if (!res.ok) {
      const parsedResponse = await res.json();
      throw new Error(parsedResponse['hydra:description']);
    }
    return { status: 1, message: null };
  } catch (e: any) {
    return { status: 0, message: e.message };
  }
}

export interface EditProfileResultType {
  status: 0 | 1;
  message: string | null;
}

export async function editprofile(
  data: (any)[]
): Promise<EditProfileResultType> {
  const token = cookies().get("session")?.value;
  const session = await getSession();
  //console.log(session);

  // On génère le formData ici car on est obligé pour envoyer un fichier`
  const formData = new FormData();
  if(data[0]) {
    formData.append("email", data[0]);
  }
  if(data[1]) {
    formData.append("pseudo", data[1]);
  }
  if(data[2] !=='') {
    formData.append("password", data[2]);
  }
  if(data[3] !== null) {
    formData.append("imageFile", data[3].get('imageFile'));
  }

  //console.log(formData);

  try {
  // Update profile with new data
    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/${session?.id}`, {
      method: "POST",
      body: formData,
      headers: {
        Authorization: `${"Bearer " + token}`,
        //"Content-Type": "application/json",
      },
    });

    const parsedResponse = await res.json();

    if (!res.ok) {
      throw new Error(parsedResponse['hydra:description']);
    }
    if (res.ok && res.status === 201) {
      cookies().set("session", parsedResponse.token);
      return { status: 1, message: language.profile.edit.toast.success };
    }
    return { status: 1, message: language.profile.edit.toast.error };
  } catch (e: any) {
    return { status: 0, message: e.message };
  }
};


export interface MotDePasseOublieResultType {
  status: 0 | 1;
  message: string | null;
}

export async function motdepasseoublie(
  formData: MotDePasseOublieFormDataType
): Promise<MotDePasseOublieResultType> {
  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/users/forgot-password`, {
      method: "POST",
      body: JSON.stringify(formData),
      headers: { "Content-Type": "application/json" },
    });

    if (!res.ok) {
      const parsedResponse = await res.json();
      throw new Error(parsedResponse.message);
    }
    return { status: 1, message: null };
  } catch (e: any) {
    return { status: 0, message: e.message };
  }
}

export interface ReinitialiserMotDePasseResultType {
  status: 0 | 1;
  message: string | null;
}

export async function reinitialisermotdepasse(
  formData: ReinitialiserMotDePasseFormDataType,
  token: string
): Promise<ReinitialiserMotDePasseResultType> {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_REST_URL}/users/forgot-password/${token}`,
      {
        method: "POST",
        body: JSON.stringify(formData),
        headers: { "Content-Type": "application/json" },
      }
    );

    const parsedResponse = await res.json();
    if (res.ok && res.status === 201) {
      cookies().set("session", parsedResponse.token);
      return { status: 1, message: null };
    }
    throw new Error(parsedResponse.message);
  } catch (e: any) {
    return { status: 0, message: e.message };
  }
}

export async function logout() {
  // Destroy the session
  cookies().set("session", "", { expires: new Date(0) });
}

export async function getSession() {
  const session = cookies().get("session")?.value;
  if (!session) return null;
  return decodeJwt(session) as {pseudo: string, username: string, avatar_url: string|null, email: string, id: string, roles: string[], exp: number, iat:number};
}
