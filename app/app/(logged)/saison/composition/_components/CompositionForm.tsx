"use client";

import { useState, useTransition, useEffect } from "react";
import { useRouter } from "next/navigation";
import Image from "next/image";
import { Block } from "@/components/custom/block";
import { SeasonAvailableDriver, SeasonAvailableTeam } from "@/actions/season-game/seasonAvailablePlayers-action";
import { createSeasonRoster } from "@/actions/season-game/createSeasonRoster-action";
import { SEASON_GAME_PAGE } from "@/constants/routing";

const STORAGE_KEY_DRIVERS = "season_composition_drivers";
const STORAGE_KEY_TEAMS = "season_composition_teams";

const BUDGET = 500;
const MAX_DRIVERS = 4;
const MAX_TEAMS = 2;

interface CompositionFormProps {
  drivers: SeasonAvailableDriver[];
  teams: SeasonAvailableTeam[];
}

const CompositionForm = ({ drivers, teams }: CompositionFormProps) => {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [activeTab, setActiveTab] = useState<"drivers" | "teams">("drivers");
  const [selectedDrivers, setSelectedDrivers] = useState<string[]>([]);
  const [selectedTeams, setSelectedTeams] = useState<string[]>([]);

  // Charger la sélection sauvegardée après le montage (évite le mismatch SSR/client)
  useEffect(() => {
    try {
      const saved = JSON.parse(localStorage.getItem(STORAGE_KEY_DRIVERS) ?? "[]");
      if (saved.length > 0) setSelectedDrivers(saved);
    } catch { /* ignore */ }
    try {
      const saved = JSON.parse(localStorage.getItem(STORAGE_KEY_TEAMS) ?? "[]");
      if (saved.length > 0) setSelectedTeams(saved);
    } catch { /* ignore */ }
  }, []);

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY_DRIVERS, JSON.stringify(selectedDrivers));
  }, [selectedDrivers]);

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY_TEAMS, JSON.stringify(selectedTeams));
  }, [selectedTeams]);
  const [error, setError] = useState<string | null>(null);

  const budgetSpent =
    selectedDrivers.reduce((sum, uuid) => {
      const d = drivers.find((d) => d.uuid === uuid);
      return sum + (d?.minValue ?? 0);
    }, 0) +
    selectedTeams.reduce((sum, uuid) => {
      const t = teams.find((t) => t.uuid === uuid);
      return sum + (t?.minValue ?? 0);
    }, 0);

  const budgetLeft = BUDGET - budgetSpent;

  const toggleDriver = (uuid: string) => {
    setSelectedDrivers((prev) => {
      if (prev.includes(uuid)) return prev.filter((d) => d !== uuid);
      if (prev.length >= MAX_DRIVERS) return prev;
      const driver = drivers.find((d) => d.uuid === uuid);
      if (!driver) return prev;
      if (budgetLeft - driver.minValue < 0) return prev;
      return [...prev, uuid];
    });
  };

  const toggleTeam = (uuid: string) => {
    setSelectedTeams((prev) => {
      if (prev.includes(uuid)) return prev.filter((t) => t !== uuid);
      if (prev.length >= MAX_TEAMS) return prev;
      const team = teams.find((t) => t.uuid === uuid);
      if (!team) return prev;
      if (budgetLeft - team.minValue < 0) return prev;
      return [...prev, uuid];
    });
  };

  const isComplete = selectedDrivers.length === MAX_DRIVERS && selectedTeams.length === MAX_TEAMS;

  const handleSubmit = () => {
    setError(null);
    startTransition(async () => {
      const result = await createSeasonRoster(selectedDrivers, selectedTeams);
      if (!result.ok) {
        if (result.alreadyExists) {
          localStorage.removeItem(STORAGE_KEY_DRIVERS);
          localStorage.removeItem(STORAGE_KEY_TEAMS);
          router.push(SEASON_GAME_PAGE);
          router.refresh();
          return;
        }
        setError(result.error ?? "Erreur lors de la validation.");
        return;
      }
      localStorage.removeItem(STORAGE_KEY_DRIVERS);
      localStorage.removeItem(STORAGE_KEY_TEAMS);
      router.push(SEASON_GAME_PAGE);
      router.refresh();
    });
  };

  const progressPct = Math.min(100, (budgetSpent / BUDGET) * 100);

  return (
    <div className="pb-32">
      {/* Budget bar */}
      <div className="zone p-4 mb-4">
        <div className="flex justify-between items-center mb-2">
          <span className="text-sm text-gray">Budget utilisé</span>
          <span className={`font-bold text-sm ${budgetLeft < 0 ? "text-red-400" : "text-primary"}`}>
            {budgetSpent} / {BUDGET} M
          </span>
        </div>
        <div className="h-2 bg-white/10 rounded-full overflow-hidden">
          <div
            className="h-full bg-primary rounded-full transition-all duration-300"
            style={{ width: `${progressPct}%` }}
          />
        </div>
        <div className="flex justify-between text-xs text-gray mt-1">
          <span>{selectedDrivers.length}/{MAX_DRIVERS} pilotes</span>
          <span>{selectedTeams.length}/{MAX_TEAMS} écuries</span>
        </div>
      </div>

      {/* Tabs */}
      <div className="bg-[#1C1D1F] p-1 rounded-full flex mb-4">
        <button
          onClick={() => setActiveTab("drivers")}
          className={`flex-1 py-2 rounded-full text-sm font-medium transition-colors ${
            activeTab === "drivers" ? "bg-primary text-black" : "text-gray"
          }`}
        >
          Pilotes ({selectedDrivers.length}/{MAX_DRIVERS})
        </button>
        <button
          onClick={() => setActiveTab("teams")}
          className={`flex-1 py-2 rounded-full text-sm font-medium transition-colors ${
            activeTab === "teams" ? "bg-primary text-black" : "text-gray"
          }`}
        >
          Écuries ({selectedTeams.length}/{MAX_TEAMS})
        </button>
      </div>

      {/* Driver list — même structure que BidItem */}
      {activeTab === "drivers" && (
        <Block containerClassName="overflow-hidden">
          {drivers.map((driver) => {
            const isSelected = selectedDrivers.includes(driver.uuid);
            const isDisabled =
              !isSelected && (selectedDrivers.length >= MAX_DRIVERS || budgetLeft < driver.minValue);
            return (
              <div
                key={driver.uuid}
                onClick={() => !isDisabled && toggleDriver(driver.uuid)}
                className={`flex items-center border-t border-white-6 h-12 ${
                  isDisabled ? "opacity-40" : "cursor-pointer"
                } ${isSelected ? "bg-primary/10" : ""}`}
              >
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src={driver.image ? `${process.env.NEXT_PUBLIC_API_URL}/${driver.image}` : "/assets/images/driver/driver-generic.svg"}
                  alt={driver.name}
                  width={50}
                  height={50}
                  className="self-end"
                />
                <div className="ml-2">
                  <h4 className="h4">{driver.name}</h4>
                  <div className="text-sm">
                    <span className="mr-[4px]">{driver.teamName}.</span>
                    <span className="text-gray inline-flex items-center">
                      Min : {driver.minValue}
                      <Image src="/assets/icons/money/m.svg" alt="" quality={100} width={12} height={12} className="ml-1" />
                    </span>
                  </div>
                </div>
                <div className="ml-auto mr-4 flex items-center">
                  <div
                    className={`w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors ${
                      isSelected ? "bg-primary border-primary" : "border-white/30"
                    }`}
                  >
                    {isSelected && (
                      <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                        <path d="M1 4L3.5 6.5L9 1" stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                      </svg>
                    )}
                  </div>
                </div>
              </div>
            );
          })}
        </Block>
      )}

      {/* Team list — même structure que BidItem */}
      {activeTab === "teams" && (
        <Block containerClassName="overflow-hidden">
          {teams.map((team) => {
            const isSelected = selectedTeams.includes(team.uuid);
            const isDisabled =
              !isSelected && (selectedTeams.length >= MAX_TEAMS || budgetLeft < team.minValue);
            return (
              <div
                key={team.uuid}
                onClick={() => !isDisabled && toggleTeam(team.uuid)}
                className={`flex items-center border-t border-white-6 h-12 ${
                  isDisabled ? "opacity-40" : "cursor-pointer"
                } ${isSelected ? "bg-primary/10" : ""}`}
              >
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src={team.image ? `${process.env.NEXT_PUBLIC_API_URL}/${team.image}` : "/assets/images/team/team-generic.svg"}
                  alt={team.name}
                  width={50}
                  height={50}
                  className="self-end object-contain"
                />
                <div className="ml-2">
                  <h4 className="h4">{team.name}</h4>
                  <div className="text-sm text-gray inline-flex items-center">
                    Min : {team.minValue}
                    <Image src="/assets/icons/money/m.svg" alt="" quality={100} width={12} height={12} className="ml-1" />
                  </div>
                </div>
                <div className="ml-auto mr-4 flex items-center">
                  <div
                    className={`w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors ${
                      isSelected ? "bg-primary border-primary" : "border-white/30"
                    }`}
                  >
                    {isSelected && (
                      <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                        <path d="M1 4L3.5 6.5L9 1" stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                      </svg>
                    )}
                  </div>
                </div>
              </div>
            );
          })}
        </Block>
      )}

      {/* Submit */}
      <div className="fixed bottom-14 pb-4 pt-4 px-4 bg-gradient-to-t from-black from-85% w-full -ml-4 max-w-[740px]">
        {error && (
          <p className="text-red-400 text-sm text-center mb-2">{error}</p>
        )}
        <button
          onClick={handleSubmit}
          disabled={!isComplete || isPending}
          className={`w-full py-3 rounded-xl font-bold text-sm transition-opacity ${
            isComplete && !isPending
              ? "bg-primary text-black"
              : "bg-primary/30 text-black/50 cursor-not-allowed"
          }`}
        >
          {isPending ? "Validation…" : "Valider ma composition"}
        </button>
        {!isComplete && (
          <p className="text-xs text-gray text-center mt-1">
            {selectedDrivers.length < MAX_DRIVERS
              ? `Sélectionne encore ${MAX_DRIVERS - selectedDrivers.length} pilote(s)`
              : `Sélectionne encore ${MAX_TEAMS - selectedTeams.length} écurie(s)`}
          </p>
        )}
      </div>
    </div>
  );
};

export { CompositionForm };
