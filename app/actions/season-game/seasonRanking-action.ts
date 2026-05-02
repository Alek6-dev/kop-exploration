"use server";

import { cookies } from "next/headers";
import { SeasonParticipation } from "./getSeasonParticipation-action";

const parseCollection = (data: any): any[] =>
  Array.isArray(data) ? data : (data["hydra:member"] ?? []);

const getSeasonRanking = async (): Promise<SeasonParticipation[]> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/ranking`, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    cache: "no-store",
  });

  if (!res.ok) return [];

  return parseCollection(await res.json());
};

const getSeasonGPRanking = async (raceUuid: string): Promise<any[]> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/ranking/gp/${raceUuid}`, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    cache: "no-store",
  });

  if (!res.ok) return [];

  return parseCollection(await res.json());
};

const getPreviousSeasons = async (): Promise<{ uuid: string; name: string }[]> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/previous-seasons`, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    cache: "no-store",
  });

  if (!res.ok) return [];

  return parseCollection(await res.json());
};

export { getSeasonRanking, getSeasonGPRanking, getPreviousSeasons };
