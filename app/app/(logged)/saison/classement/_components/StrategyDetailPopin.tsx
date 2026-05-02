"use client";

import { SeasonGPRankingEntry } from "./ClassementTabs";

interface StrategyDetailPopinProps {
  entry: SeasonGPRankingEntry | null;
  onClose: () => void;
}

const StrategyDetailPopin = ({ entry, onClose }: StrategyDetailPopinProps) => {
  if (!entry) return null;

  return (
    <div
      className="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/70"
      onClick={onClose}
    >
      <div
        className="zone w-full max-w-md mx-4 mb-4 sm:mb-0 overflow-hidden"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="p-4 border-b border-white/6 flex items-center justify-between">
          <div>
            <p className="text-xs text-gray uppercase tracking-wider">Stratégie</p>
            <p className="font-bold">{entry.userPseudo ?? "Joueur"}</p>
          </div>
          <button
            onClick={onClose}
            className="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-gray hover:text-white transition-colors"
          >
            ×
          </button>
        </div>

        <div className="p-4 space-y-3">
          <div className="flex items-center gap-3 py-2 border-b border-white/6">
            <div className="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
              <span className="text-xs font-bold text-primary">P1</span>
            </div>
            <div className="flex-1">
              <p className="text-xs text-gray">Pilote P1</p>
              <p className="font-medium">{entry.driver1?.name ?? "—"}</p>
            </div>
            <span className="text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full">×2</span>
          </div>

          <div className="flex items-center gap-3 py-2 border-b border-white/6">
            <div className="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
              <span className="text-xs font-bold">P2</span>
            </div>
            <div className="flex-1">
              <p className="text-xs text-gray">Pilote P2</p>
              <p className="font-medium">{entry.driver2?.name ?? "—"}</p>
            </div>
          </div>

          <div className="flex items-center gap-3 py-2 border-b border-white/6">
            <div className="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
              <span className="text-xs font-bold">E</span>
            </div>
            <div className="flex-1">
              <p className="text-xs text-gray">Écurie</p>
              <p className="font-medium">{entry.team?.name ?? "—"}</p>
            </div>
          </div>

          {entry.bonuses && entry.bonuses.length > 0 && (
            <div className="pt-1">
              <p className="text-xs text-gray uppercase tracking-wider mb-2">Bonus utilisés</p>
              <div className="space-y-1">
                {entry.bonuses.map((b, i) => (
                  <div key={i} className="flex items-center justify-between text-sm">
                    <span>{b.label}</span>
                    <span className="text-gray">{b.pricePaid} ¤</span>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>

        <div className="px-4 py-3 bg-primary/5 border-t border-white/6 flex items-center justify-between">
          <span className="text-sm text-gray">Score GP</span>
          <span className="text-xl font-bold text-primary">{entry.points ?? "—"} pts</span>
        </div>
      </div>
    </div>
  );
};

export { StrategyDetailPopin };
