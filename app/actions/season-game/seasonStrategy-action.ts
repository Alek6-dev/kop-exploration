"use server";

import { cookies } from "next/headers";

export interface SeasonGPStrategy {
  uuid: string;
  raceUuid: string;
  raceName: string;
  driver1: { uuid: string; driverUuid: string; name: string } | null;
  driver2: { uuid: string; driverUuid: string; name: string } | null;
  team: { uuid: string; teamUuid: string; name: string } | null;
  points: number | null;
  locked: boolean;
  bonuses: { type: string; label: string; pricePaid: number }[];
}

const getSeasonGPStrategy = async (raceUuid: string): Promise<SeasonGPStrategy | null> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/strategy/${raceUuid}`, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
    cache: "no-store",
  });

  if (res.status === 404 || !res.ok) return null;

  return res.json();
};

const saveSeasonGPStrategy = async (
  raceUuid: string,
  driver1Uuid: string,
  driver2Uuid: string,
  teamUuid: string
): Promise<{ ok: boolean; data?: SeasonGPStrategy; error?: string }> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/strategy/${raceUuid}`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
      "Accept": "application/json",
    },
    body: JSON.stringify({ driver1Uuid, driver2Uuid, teamUuid }),
  });

  const text = await res.text();

  if (!res.ok) {
    try {
      const data = JSON.parse(text);
      const message = data.detail ?? data["hydra:description"] ?? data.message ?? "Erreur lors de la sauvegarde.";
      return { ok: false, error: message };
    } catch {
      return { ok: false, error: `Erreur ${res.status}` };
    }
  }

  return { ok: true };
};

const applySeasonBonus = async (
  raceUuid: string,
  bonusType: string
): Promise<{ ok: boolean; error?: string }> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/strategy/${raceUuid}/bonus`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ raceUuid, bonusType }),
  });

  if (!res.ok) {
    const data = await res.json();
    return { ok: false, error: data.detail ?? "Erreur lors de l'application du bonus" };
  }

  return { ok: true };
};

export { getSeasonGPStrategy, saveSeasonGPStrategy, applySeasonBonus };
