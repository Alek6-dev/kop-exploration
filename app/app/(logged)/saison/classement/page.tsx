import { redirect } from "next/navigation";
import { getSession } from "@/lib/security";
import { getSeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import { getSeasonRanking, getSeasonGPRanking } from "@/actions/season-game/seasonRanking-action";
import { getSeasonScoredRaces } from "@/actions/season-game/getSeasonScoredRaces-action";
import { LOGIN_PAGE, SEASON_GAME_PAGE } from "@/constants/routing";
import { SeasonLayout } from "../_components/SeasonLayout";
import { ClassementTabs, SeasonGPRankingEntry } from "./_components/ClassementTabs";

interface PageProps {
  searchParams: { raceUuid?: string };
}

export default async function SeasonClassementPage({ searchParams }: PageProps) {
  const session = await getSession();
  if (!session) redirect(LOGIN_PAGE);

  const participation = await getSeasonParticipation();
  if (!participation) redirect(SEASON_GAME_PAGE);

  const raceUuid = searchParams.raceUuid;

  const [globalRanking, scoredRaces, gpRanking] = await Promise.all([
    getSeasonRanking(),
    getSeasonScoredRaces(),
    raceUuid ? getSeasonGPRanking(raceUuid) : Promise.resolve([]),
  ]);

  // Default to most recent scored race if none selected
  const selectedRaceUuid = raceUuid ?? (scoredRaces[0]?.uuid ?? null);

  // Fetch GP ranking for default race if no raceUuid in URL
  const finalGPRanking = gpRanking.length === 0 && selectedRaceUuid && !raceUuid
    ? await getSeasonGPRanking(selectedRaceUuid)
    : gpRanking;

  const myPosition = globalRanking.findIndex((p) => p.uuid === participation.uuid) + 1;
  const initialTab = raceUuid ? "gp" : "saison";

  return (
    <SeasonLayout participation={participation}>
      <ClassementTabs
        globalRanking={globalRanking}
        gpRanking={finalGPRanking as SeasonGPRankingEntry[]}
        scoredRaces={scoredRaces}
        myUuid={participation.uuid}
        myUserUuid={participation.userUuid}
        myPosition={myPosition}
        myPoints={participation.totalPoints}
        selectedRaceUuid={selectedRaceUuid}
        initialTab={initialTab}
      />
    </SeasonLayout>
  );
}
