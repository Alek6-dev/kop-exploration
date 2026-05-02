import { redirect } from "next/navigation";
import { getSession } from "@/lib/security";
import { getSeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import { getPreviousSeasons } from "@/actions/season-game/seasonRanking-action";
import { LOGIN_PAGE, SEASON_GAME_PAGE } from "@/constants/routing";
import { SeasonLayout } from "../_components/SeasonLayout";

export default async function SeasonPalmaresPage() {
  const session = await getSession();
  if (!session) redirect(LOGIN_PAGE);

  const participation = await getSeasonParticipation();
  if (!participation) redirect(SEASON_GAME_PAGE);

  const previousSeasons = await getPreviousSeasons();

  return (
    <SeasonLayout participation={participation}>
      <div className="space-y-4">
        <div className="zone p-4">
          <h2 className="font-bold mb-1">Palmarès</h2>
          <p className="text-sm text-gray">Saisons précédentes</p>
        </div>

        {previousSeasons.length === 0 ? (
          <div className="zone p-6 text-center">
            <p className="text-2xl mb-2">🏆</p>
            <p className="text-gray text-sm">Aucune saison précédente pour le moment.</p>
          </div>
        ) : (
          <div className="zone overflow-hidden">
            {previousSeasons.map((season) => (
              <div
                key={season.uuid}
                className="flex items-center gap-3 px-4 py-3 border-b border-white/6"
              >
                <div className="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                  <span className="text-primary text-xs">🏆</span>
                </div>
                <div className="flex-1">
                  <p className="font-medium">{season.name}</p>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </SeasonLayout>
  );
}
