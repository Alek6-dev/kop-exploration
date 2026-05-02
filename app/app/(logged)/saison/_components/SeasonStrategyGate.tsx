import { Block } from "@/components/custom/block";
import { SeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import { getSeasonGPStrategy } from "@/actions/season-game/seasonStrategy-action";
import { SeasonStrategyForm } from "../strategie/[raceUuid]/_components/SeasonStrategyForm";

interface SeasonStrategyGateProps {
  participation: SeasonParticipation;
}

const SeasonStrategyGate = async ({ participation }: SeasonStrategyGateProps) => {
  try {
    const { nextRace } = participation;

    if (!nextRace) {
      return (
        <Block containerClassName="mt-4">
          <div className="p-6 text-center">
            <p className="font-semibold">Saison terminée</p>
            <p className="text-sm text-gray mt-1">Pas de prochain GP planifié. Consulte le palmarès !</p>
          </div>
        </Block>
      );
    }

    if (!participation.roster) {
      return (
        <Block containerClassName="mt-4">
          <div className="p-6 text-center">
            <p className="font-semibold">Roster introuvable</p>
            <p className="text-sm text-gray mt-1">Une erreur est survenue. Recharge la page.</p>
          </div>
        </Block>
      );
    }

    const strategy = await getSeasonGPStrategy(nextRace.uuid);

    const limitDate = new Date(nextRace.limitStrategyDate);

    return (
      <div className="space-y-4">
        <div className="flex items-center justify-between px-1">
          <div>
            <p className="text-xs text-gray uppercase tracking-wider">Prochain GP</p>
            <h2 className="font-bold text-lg">{nextRace.name}</h2>
            {nextRace.isSprintWeekend && (
              <span className="text-xs bg-primary/20 text-primary px-2 py-0.5 rounded-full">Sprint</span>
            )}
          </div>
          <div className="text-right">
            <p className="text-xs text-gray">Clôture</p>
            <p className="text-sm font-medium">{limitDate.toLocaleDateString("fr-FR", { day: "numeric", month: "short" })}</p>
            <p className="text-xs text-gray">{limitDate.toLocaleTimeString("fr-FR", { hour: "2-digit", minute: "2-digit" })}</p>
          </div>
        </div>

        <SeasonStrategyForm
          raceUuid={nextRace.uuid}
          roster={participation.roster}
          existingStrategy={strategy}
        />
      </div>
    );
  } catch (err) {
    console.error("[SeasonStrategyGate] CRASH:", err);
    return (
      <div className="zone p-4">
        <div className="text-red-400 text-sm">Erreur: {String(err)}</div>
      </div>
    );
  }
};

export { SeasonStrategyGate };
