import { redirect } from "next/navigation";
import { getSession } from "@/lib/security";
import { getSeasonParticipation, enrollInSeason } from "@/actions/season-game/getSeasonParticipation-action";
import { getSeasonAvailableDrivers, getSeasonAvailableTeams } from "@/actions/season-game/seasonAvailablePlayers-action";
import { LOGIN_PAGE, SEASON_GAME_PAGE } from "@/constants/routing";
import { Container } from "@/components/custom/container";
import { CompositionForm } from "./_components/CompositionForm";

export default async function SeasonCompositionPage() {
  const session = await getSession();
  if (!session) redirect(LOGIN_PAGE);

  let participation = await getSeasonParticipation();
  if (!participation) {
    participation = await enrollInSeason();
  }

  if (!participation) {
    redirect(SEASON_GAME_PAGE);
  }

  if (participation.hasRoster) {
    redirect(SEASON_GAME_PAGE);
  }

  const [drivers, teams] = await Promise.all([
    getSeasonAvailableDrivers(),
    getSeasonAvailableTeams(),
  ]);

  return (
    <Container className="py-4">
      <div className="mb-6">
        <h1 className="text-xl font-bold">Ma composition</h1>
        <p className="text-sm text-gray mt-1">
          Sélectionne 4 pilotes et 2 écuries. Budget total : <span className="text-primary font-bold">500 M</span>
        </p>
      </div>
      <CompositionForm drivers={drivers} teams={teams} />
    </Container>
  );
}
