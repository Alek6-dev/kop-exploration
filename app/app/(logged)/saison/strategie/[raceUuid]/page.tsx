import { redirect } from "next/navigation";
import { getSession } from "@/lib/security";
import { getSeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import { getSeasonGPStrategy } from "@/actions/season-game/seasonStrategy-action";
import { LOGIN_PAGE, SEASON_GAME_PAGE, SEASON_GAME_COMPOSITION_PAGE } from "@/constants/routing";
import { Container } from "@/components/custom/container";
import { SeasonStrategyForm } from "./_components/SeasonStrategyForm";

interface Props {
  params: { raceUuid: string };
}

export default async function SeasonStrategyPage({ params }: Props) {
  const session = await getSession();
  if (!session) redirect(LOGIN_PAGE);

  const participation = await getSeasonParticipation();
  if (!participation) redirect(SEASON_GAME_PAGE);
  if (!participation.hasRoster || !participation.roster) redirect(SEASON_GAME_COMPOSITION_PAGE);

  const strategy = await getSeasonGPStrategy(params.raceUuid);

  const race = participation.nextRace?.uuid === params.raceUuid ? participation.nextRace : null;

  return (
    <Container className="py-4">
      <div className="mb-4">
        <p className="text-xs text-gray uppercase tracking-wider">Stratégie GP</p>
        <h1 className="text-xl font-bold">{race?.name ?? "Grand Prix"}</h1>
        {race && (
          <p className="text-sm text-gray">
            Clôture le {new Date(race.limitStrategyDate).toLocaleDateString("fr-FR", {
              day: "numeric",
              month: "long",
              hour: "2-digit",
              minute: "2-digit",
            })}
          </p>
        )}
      </div>

      <SeasonStrategyForm
        raceUuid={params.raceUuid}
        roster={participation.roster}
        existingStrategy={strategy}
      />
    </Container>
  );
}
