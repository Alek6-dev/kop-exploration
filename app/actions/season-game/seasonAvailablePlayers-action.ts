"use server";

import { cookies } from "next/headers";

export interface SeasonAvailableDriver {
  uuid: string;
  name: string;
  minValue: number;
  teamName: string;
  teamColor: string;
  image: string | null;
}

export interface SeasonAvailableTeam {
  uuid: string;
  name: string;
  minValue: number;
  color: string;
  image: string | null;
}

const getSeasonAvailableDrivers = async (): Promise<SeasonAvailableDriver[]> => {
  const token = cookies().get("session")?.value;
  const url = `${process.env.NEXT_PUBLIC_REST_URL}/season-game/available-drivers`;
  console.log("[SeasonDrivers] fetching", url, "token:", token ? "present" : "missing");

  let res: Response;
  try {
    res = await fetch(url, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      cache: "no-store",
    });
  } catch (err) {
    console.error("[SeasonDrivers] fetch threw:", err);
    return [];
  }

  if (!res.ok) {
    console.error("[SeasonDrivers] fetch failed", res.status, await res.text());
    return [];
  }

  const data = await res.json();
  console.log("[SeasonDrivers] ok, count:", Array.isArray(data) ? data.length : data["hydra:member"]?.length ?? "?");
  return Array.isArray(data) ? data : (data["hydra:member"] ?? []);
};

const getSeasonAvailableTeams = async (): Promise<SeasonAvailableTeam[]> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/available-teams`, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    cache: "no-store",
  });

  if (!res.ok) {
    console.error("[SeasonTeams] fetch failed", res.status, await res.text());
    return [];
  }

  const data = await res.json();
  console.log("[SeasonTeams] raw data type:", Array.isArray(data) ? "array" : typeof data, "length:", Array.isArray(data) ? data.length : Object.keys(data).length);
  return Array.isArray(data) ? data : (data["hydra:member"] ?? []);
};

export { getSeasonAvailableDrivers, getSeasonAvailableTeams };
