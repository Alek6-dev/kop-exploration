"use server";

import { cookies } from "next/headers";

export interface SeasonRosterDriver {
  uuid: string;
  driverUuid: string;
  driverName: string;
  driverImage: string | null;
  teamName: string | null;
  teamColor: string | null;
  purchasePrice: number;
  maxUsages: number;
  usagesLeft: number;
}

export interface SeasonRosterTeam {
  uuid: string;
  teamUuid: string;
  teamName: string;
  teamColor: string | null;
  teamImage: string | null;
  purchasePrice: number;
  maxUsages: number;
  usagesLeft: number;
}

export interface SeasonRoster {
  uuid: string;
  budgetSpent: number;
  validatedAt: string;
  drivers: SeasonRosterDriver[];
  teams: SeasonRosterTeam[];
}

export interface SeasonNextRace {
  uuid: string;
  name: string;
  date: string;
  limitStrategyDate: string;
  isSprintWeekend: boolean;
}

export interface SeasonParticipation {
  uuid: string;
  totalPoints: number;
  walletBalance: number;
  enrolledAt: string;
  hasRoster: boolean;
  roster: SeasonRoster | null;
  seasonName: string;
  seasonActive: boolean;
  nextRace: SeasonNextRace | null;
  userPseudo: string | null;
  userUuid: string | null;
}

const getSeasonParticipation = async (): Promise<SeasonParticipation | null> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/me`, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
    cache: "no-store",
  });

  const text = await res.text();

  if (res.status === 404 || !res.ok) {
    console.error("[getSeasonParticipation] status:", res.status, text.slice(0, 300));
    return null;
  }

  try {
    return JSON.parse(text);
  } catch {
    console.error("[getSeasonParticipation] invalid JSON:", text.slice(0, 300));
    return null;
  }
};

const enrollInSeason = async (): Promise<SeasonParticipation | null> => {
  const token = cookies().get("session")?.value;
  const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/season-game/enroll`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({}),
  });

  const text = await res.text();
  if (!res.ok) return null;

  try {
    return JSON.parse(text);
  } catch {
    console.error("[enrollInSeason] invalid JSON:", text.slice(0, 300));
    return null;
  }
};

export { getSeasonParticipation, enrollInSeason };
