"use client";

import { useState } from "react";
import { SeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import { SeasonScoredRace } from "@/actions/season-game/getSeasonScoredRaces-action";
import { SeasonGPSelector } from "./SeasonGPSelector";
import { StrategyDetailPopin } from "./StrategyDetailPopin";

export interface SeasonGPRankingEntry {
  uuid: string;
  raceUuid: string;
  raceName: string | null;
  driver1: { uuid: string; driverUuid: string; name: string } | null;
  driver2: { uuid: string; driverUuid: string; name: string } | null;
  team: { uuid: string; teamUuid: string; name: string } | null;
  points: number | null;
  userPseudo: string | null;
  userUuid: string | null;
  bonuses: { type: string; label: string; pricePaid: number }[];
}

interface ClassementTabsProps {
  globalRanking: SeasonParticipation[];
  gpRanking: SeasonGPRankingEntry[];
  scoredRaces: SeasonScoredRace[];
  myUuid: string;
  myUserUuid: string | null;
  myPosition: number;
  myPoints: number;
  selectedRaceUuid: string | null;
  initialTab: "saison" | "gp";
}

const gpBadgeClass = (position: number, isMe: boolean) => {
  if (position === 1) return "bg-primary text-black";
  if (position === 2) return "bg-white/30 text-white";
  if (position === 3) return "bg-white/20 text-white";
  if (isMe) return "bg-primary/30 text-primary";
  return "bg-white/10 text-white";
};

const ClassementTabs = ({
  globalRanking,
  gpRanking,
  scoredRaces,
  myUuid,
  myUserUuid,
  myPosition,
  myPoints,
  selectedRaceUuid,
  initialTab,
}: ClassementTabsProps) => {
  const [activeTab, setActiveTab] = useState<"saison" | "gp">(initialTab);
  const [detailEntry, setDetailEntry] = useState<SeasonGPRankingEntry | null>(null);

  return (
    <>
      <StrategyDetailPopin entry={detailEntry} onClose={() => setDetailEntry(null)} />

      <div className="space-y-4">
        <div className="flex bg-white/5 rounded-full p-1 gap-1">
          <button
            onClick={() => setActiveTab("saison")}
            className={`flex-1 py-1.5 text-sm font-medium rounded-full transition-colors ${
              activeTab === "saison" ? "bg-primary text-black" : "text-gray hover:text-white"
            }`}
          >
            Saison
          </button>
          <button
            onClick={() => setActiveTab("gp")}
            className={`flex-1 py-1.5 text-sm font-medium rounded-full transition-colors ${
              activeTab === "gp" ? "bg-primary text-black" : "text-gray hover:text-white"
            }`}
          >
            Grand-Prix
          </button>
        </div>

        {activeTab === "saison" && (() => {
          const TOP_N = 5;
          const topRows = globalRanking.slice(0, TOP_N);
          const myParticipation = globalRanking.find((p) => p.uuid === myUuid);
          const isInTop = myPosition > 0 && myPosition <= TOP_N;

          const renderSaisonRow = (p: SeasonParticipation, position: number, isMe: boolean) => {
            let badgeClass = "bg-white/10 text-white";
            if (position === 1) badgeClass = "bg-primary text-black";
            else if (position === 2) badgeClass = "bg-white/30 text-white";
            else if (position === 3) badgeClass = "bg-white/20 text-white";
            else if (isMe) badgeClass = "bg-primary/30 text-primary";

            return (
              <div
                key={p.uuid}
                className={`flex items-center gap-3 px-4 py-3 border-b border-white/6 last:border-b-0 ${isMe ? "bg-primary/5 border-l-2 border-l-primary" : ""}`}
              >
                <div className={`w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 ${badgeClass}`}>
                  {position}
                </div>
                <p className={`flex-1 font-medium truncate ${isMe ? "text-primary" : ""}`}>
                  {p.userPseudo ?? "Joueur"}{isMe && " (moi)"}
                </p>
                <p className="font-bold text-sm flex-shrink-0">{p.totalPoints} pts</p>
              </div>
            );
          };

          return (
            <div className="zone overflow-hidden">
              <div className="p-4 pb-2 flex items-center justify-between">
                <div>
                  <h2 className="font-bold">Classement saison</h2>
                  <p className="text-xs text-gray">{globalRanking.length} participants</p>
                </div>
                {myPosition > 0 && (
                  <div className="text-right">
                    <p className="text-xs text-gray">Ta position</p>
                    <p className="text-xl font-bold text-primary">#{myPosition}</p>
                  </div>
                )}
              </div>

              {topRows.map((p, index) => renderSaisonRow(p, index + 1, p.uuid === myUuid))}

              {!isInTop && myParticipation && myPosition > 0 && (
                <>
                  <div className="flex items-center gap-1 px-4 py-2 border-b border-white/6">
                    <span className="w-1 h-1 rounded-full bg-white/20" />
                    <span className="w-1 h-1 rounded-full bg-white/20" />
                    <span className="w-1 h-1 rounded-full bg-white/20" />
                  </div>
                  {renderSaisonRow(myParticipation, myPosition, true)}
                </>
              )}
            </div>
          );
        })()}

        {activeTab === "gp" && (() => {
          const TOP_N = 3;
          const gpMyIndex = gpRanking.findIndex((e) => e.userUuid === myUserUuid);
          const gpMyPosition = gpMyIndex + 1;
          const isInGPTop = gpMyPosition > 0 && gpMyPosition <= TOP_N;

          // Build context rows (user-1, user, user+1) avoiding overlap with top 3
          const contextStart = gpMyPosition > TOP_N
            ? Math.max(TOP_N, gpMyIndex - 1)
            : -1;
          const contextEnd = gpMyPosition > TOP_N
            ? Math.min(gpMyIndex + 1, gpRanking.length - 1)
            : -1;
          const showSeparator = contextStart > TOP_N;

          const renderGPRow = (entry: SeasonGPRankingEntry, position: number, isMe: boolean) => (
            <div
              key={entry.uuid}
              className={`flex items-center gap-3 px-4 py-3 border-b border-white/6 last:border-b-0 ${isMe ? "bg-primary/5 border-l-2 border-l-primary" : ""}`}
            >
              <div className={`w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 ${gpBadgeClass(position, isMe)}`}>
                {position}
              </div>
              <div className="flex-1 min-w-0 space-y-0.5">
                <p className={`font-medium truncate ${isMe ? "text-primary" : ""}`}>
                  {entry.userPseudo ?? "—"}{isMe && " (moi)"}
                </p>
                <p className="text-xs text-gray truncate">
                  {entry.driver1?.name ?? "—"} ×2 · {entry.driver2?.name ?? "—"} · {entry.team?.name ?? "—"}
                </p>
              </div>
              <div className="flex items-center gap-2 flex-shrink-0">
                <p className="font-bold text-sm">{entry.points ?? "—"} pts</p>
                <button
                  onClick={() => setDetailEntry(entry)}
                  className="text-xs text-gray hover:text-white border border-white/10 rounded px-2 py-0.5 transition-colors"
                >
                  Détail
                </button>
              </div>
            </div>
          );

          return (
            <div className="space-y-4">
              <SeasonGPSelector races={scoredRaces} selectedUuid={selectedRaceUuid} />

              {scoredRaces.length > 0 && gpRanking.length > 0 && (
                <div className="zone overflow-hidden">
                  <div className="p-4 pb-2 flex items-center justify-between">
                    <div>
                      <h2 className="font-bold">Classement GP</h2>
                      <p className="text-xs text-gray">{gpRanking.length} stratégies</p>
                    </div>
                    {gpMyPosition > 0 && (
                      <div className="text-right">
                        <p className="text-xs text-gray">Ta position</p>
                        <p className="text-xl font-bold text-primary">#{gpMyPosition}</p>
                      </div>
                    )}
                  </div>

                  {gpRanking.slice(0, TOP_N).map((entry, index) =>
                    renderGPRow(entry, index + 1, entry.userUuid === myUserUuid)
                  )}

                  {!isInGPTop && gpMyPosition > 0 && contextStart >= 0 && (
                    <>
                      {showSeparator && (
                        <div className="flex items-center gap-1 px-4 py-2 border-b border-white/6">
                          <span className="w-1 h-1 rounded-full bg-white/20" />
                          <span className="w-1 h-1 rounded-full bg-white/20" />
                          <span className="w-1 h-1 rounded-full bg-white/20" />
                        </div>
                      )}
                      {gpRanking.slice(contextStart, contextEnd + 1).map((entry, i) =>
                        renderGPRow(entry, contextStart + i + 1, entry.userUuid === myUserUuid)
                      )}
                    </>
                  )}
                </div>
              )}

              {scoredRaces.length > 0 && gpRanking.length === 0 && (
                <div className="zone">
                  <div className="px-4 py-8 text-center text-sm text-gray">
                    Aucun résultat pour ce GP
                  </div>
                </div>
              )}
            </div>
          );
        })()}
      </div>
    </>
  );
};

export { ClassementTabs };
