"use server";

import { cookies } from "next/headers";

export interface SeasonScoredRace {
  uuid: string;
  name: string;
}

const getSeasonScoredRaces = async (): Promise<SeasonScoredRace[]> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/scored-races`, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    cache: "no-store",
  });

  if (!res.ok) return [];

  const data = await res.json();
  return Array.isArray(data) ? data : (data["hydra:member"] ?? []);
};

export { getSeasonScoredRaces };
