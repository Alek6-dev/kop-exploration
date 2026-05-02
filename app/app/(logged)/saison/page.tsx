import { Suspense } from "react";
import { getSession } from "@/lib/security";
import { redirect } from "next/navigation";
import { LOGIN_PAGE, SEASON_GAME_COMPOSITION_PAGE } from "@/constants/routing";
import { enrollInSeason, getSeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import { Container } from "@/components/custom/container";
import { SeasonLayout } from "./_components/SeasonLayout";
import { SeasonStrategyGate } from "./_components/SeasonStrategyGate";

export default async function SeasonPage() {
  const session = await getSession();
  console.log("[SeasonPage] session:", session ? "ok" : "NULL → redirect login");
  if (!session) redirect(LOGIN_PAGE);

  let participation = await getSeasonParticipation();
  console.log("[SeasonPage] participation:", participation ? `uuid=${participation.uuid} hasRoster=${participation.hasRoster}` : "NULL");

  if (!participation) {
    participation = await enrollInSeason();
    console.log("[SeasonPage] enrollInSeason:", participation ? `uuid=${participation.uuid}` : "NULL");
  }

  if (!participation) {
    return (
      <Container>
        <div className="zone p-6 text-center mt-8">
          <p className="text-gray">Aucune saison active pour le moment.</p>
          <p className="text-sm text-gray mt-2">Reviens en mars pour la prochaine saison !</p>
        </div>
      </Container>
    );
  }

  console.log("[SeasonPage] hasRoster:", participation.hasRoster, "nextRace:", participation.nextRace?.uuid ?? "null");

  if (!participation.hasRoster) {
    redirect(SEASON_GAME_COMPOSITION_PAGE);
  }

  return (
    <SeasonLayout participation={participation}>
      <SeasonStrategyGate participation={participation} />
    </SeasonLayout>
  );
}
