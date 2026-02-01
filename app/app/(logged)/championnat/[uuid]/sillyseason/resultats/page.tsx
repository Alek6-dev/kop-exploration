import { Block } from "@/components/custom/block";
import { Container } from "@/components/custom/container";
import { Player } from "@/components/custom/player";
import { LOGIN_PAGE, NOTFOUND_PAGE } from "@/constants/routing";
import { getSession } from "@/lib/security";
import language from "@/messages/fr";
import { redirect } from "next/navigation";
import { ItemWon } from "./_components/ItemWon";
import { cookies } from "next/headers";
import { ChampionshipPlayerArray } from "@/type/championship";

export interface ResultsArray {
  name: string|null;
  uuidItem?: string;
  type?: string;
  image?: string;
  color?: string;
  amount?: number;
  round?: number;
  assignBySystem?: boolean;

}

export interface PlayerArray {
  uuid: string;
  name: string;
  carColor: string;
  bettingRoundDriver1Won?: ResultsArray;
  bettingRoundDriver2Won?: ResultsArray;
  bettingRoundTeamWon?: ResultsArray;
}

export default async function SillySeasonResults({params : { uuid }}: {params: { uuid: string }}) {
  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }

  const fetchData = async () => {
    try {
      const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}`, { headers });
      const parsedResponse = await res.json();
      return parsedResponse;
    } catch (err) {
      throw err;
    }
  };

  const championshipData = await fetchData();
  const championshipActiveStatus = 5;

  const lastRound = championshipData.status >= championshipActiveStatus ? championshipData.currentRound : championshipData.currentRound - 1;

  // Translations with variables
  const title = language.championship.sillyseason_results.title
  .replace("{championship_name}", championshipData.name);

  return(
    <main>
      <Container>
      <h1
          className="h1"
          dangerouslySetInnerHTML={{
          __html: title,
          }}
      ></h1>
      </Container>

      <Container className="mt-6">
        <Block containerClassName="block-animation p-4 mb-4" childClassName="overflow-hidden">
          <h2 className="h3 mb-2 text-primary">{language.championship.sillyseason_results.results}</h2>
          <p className="text-gray">{language.championship.sillyseason_results.description}</p>
        </Block>
        {championshipData.players.map((player: ChampionshipPlayerArray) => (
          <Block key={String(player.uuid)} containerClassName="block-animation mt-2 overflow-hidden pb-2" childClassName="overflow-hidden">
            <Player name={player.name} user={player.user} carClassName="w-16 h-10 -translate-y-2 rounded-t-none rounded-bl-lg" titleClassName="h3" divClassName="pb-1 pt-1" />
            {player.bettingRoundDriver1Won &&
              <ItemWon key={String(player.bettingRoundDriver1Won.uuidItem)} result={player.bettingRoundDriver1Won} lastRound={lastRound} type="driver" />
            }
            {player.bettingRoundDriver2Won &&
              <ItemWon key={String(player.bettingRoundDriver2Won.uuidItem)} result={player.bettingRoundDriver2Won} lastRound={lastRound} type="driver" />
            }
            {player.bettingRoundTeamWon &&
              <ItemWon key={String(player.bettingRoundTeamWon.uuidItem)} result={player.bettingRoundTeamWon} lastRound={lastRound} type="team" />
            }
          </Block>
        ))}
      </Container>
    </main>
  )
}
