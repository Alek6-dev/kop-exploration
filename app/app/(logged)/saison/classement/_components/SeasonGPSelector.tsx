"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import IconDropdownArrow from "@/public/assets/icons/small/dropdown-arrow.svg";
import { SEASON_GAME_RANKING_PAGE } from "@/constants/routing";
import { SeasonScoredRace } from "@/actions/season-game/getSeasonScoredRaces-action";

export type { SeasonScoredRace };

interface SeasonGPSelectorProps {
  races: SeasonScoredRace[];
  selectedUuid: string | null;
}

const SeasonGPSelector = ({ races, selectedUuid }: SeasonGPSelectorProps) => {
  const router = useRouter();
  const [open, setOpen] = useState(false);

  if (races.length === 0) {
    return (
      <div className="zone">
        <div className="px-4 py-3 text-sm text-gray">
          Aucun GP scoré pour l'instant
        </div>
      </div>
    );
  }

  const selectedIndex = races.findIndex((r) => r.uuid === selectedUuid);
  const selected = selectedIndex >= 0 ? races[selectedIndex] : races[0];
  const displayIndex = selectedIndex >= 0 ? selectedIndex : 0;

  const handleSelect = (uuid: string) => {
    setOpen(false);
    router.push(`${SEASON_GAME_RANKING_PAGE}?raceUuid=${uuid}`);
  };

  return (
    <div className="relative z-10">
      <button
        className="w-full border border-white/6 bg-black/15 flex items-center gap-4 px-4 py-3 rounded-[10px]"
        onClick={() => setOpen(!open)}
      >
        <div className="flex-1 text-left">
          <p className="text-xs text-gray uppercase tracking-wider mb-0.5">
            GP {races.length - displayIndex}
          </p>
          <p className="font-medium leading-tight">{selected.name}</p>
        </div>
        <div className="h-8 w-8 flex-shrink-0 border rounded-[10px] border-white/10 flex items-center justify-center">
          <IconDropdownArrow />
        </div>
      </button>

      {open && (
        <div className="w-full flex flex-col absolute top-full mt-1 left-0 bg-black border border-white/6 shadow-[0_50px_100px_0_rgba(0,0,0,0.8)] rounded-[10px] overflow-hidden">
          {races.map((race, index) => (
            <button
              key={race.uuid}
              onClick={() => handleSelect(race.uuid)}
              className={`flex items-center gap-3 w-full px-4 py-3 text-left transition-colors hover:bg-white/5 ${
                index + 1 < races.length ? "border-b border-white/10" : ""
              } ${race.uuid === selected.uuid ? "text-primary" : ""}`}
            >
              <span className="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center text-xs flex-shrink-0">
                {races.length - index}
              </span>
              <span className="flex-1 font-medium">{race.name}</span>
            </button>
          ))}
        </div>
      )}
    </div>
  );
};

export { SeasonGPSelector };
