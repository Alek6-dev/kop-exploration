"use server";

import { cookies } from "next/headers";
import { SeasonRoster } from "./getSeasonParticipation-action";

const createSeasonRoster = async (driverUuids: string[], teamUuids: string[]): Promise<{ ok: boolean; data?: SeasonRoster; error?: string; alreadyExists?: boolean }> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/roster`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ driverUuids, teamUuids }),
  });

  const data = await res.json();

  if (!res.ok) {
    return { ok: false, error: data.detail ?? data["hydra:description"] ?? "Erreur lors de la création de l'équipe", alreadyExists: res.status === 409 };
  }

  return { ok: true, data };
};

export { createSeasonRoster };
