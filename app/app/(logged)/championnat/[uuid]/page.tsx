import { getSession } from "@/lib/security";
import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { ShareButton } from "@/components/custom/shareButton";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { CHAMPIONSHIP_SILLYSEASON_PAGE, FORGOT_PASSWORD_PAGE, LOGIN_PAGE, NOTFOUND_PAGE } from "@/constants/routing";
import { Player } from "@/components/custom/player";
import { CancelChampionshipButton } from "./_components/CancelChampionshipButton";
import { StartChampionshipButton } from "./_components/StartChampionshipButton";
import { ChampionshipPlayerUserArray, championshipPlayerCosmeticArray } from "@/type/championship";

export default async function LobbyChampionship({params : { uuid }}: {params: { uuid: string }}) {
  const session = await getSession();

  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }

  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}`, { headers });
    const championshipData = await res.json();

    if (!res.ok) {
      throw new Error(championshipData.message);
    }

    // If championship is already started, redirect to silly season page via an error catched
    if(championshipData.status === 2) {
      throw new Error("silly_season_started");
    }

    const playersRegistered = championshipData.players.length;

    // Authorize manual launch if there is at least 4 players registered and the number of players is even
    let disabledManualLaunch = true;
    if (playersRegistered > 3) {
      if(playersRegistered % 2 === 0) {
        disabledManualLaunch = false;
      }
    }

    const isCreator = Boolean(championshipData.createdBy.uuid === session?.id);

    // Translations with variables
    const title = language.championship.invitation.title
    .replace("{championship_name}", championshipData.name);

    const description = language.championship.invitation.section.share_code.description.label
    .replace("{championship_name}", championshipData.name)
    .replace("{icon}", "<img src='/assets/icons/money/kop.svg' alt='' width='18' height='auto' class='inline-flex'>");

    const players = language.championship.invitation.section.players.title.label
    .replace("{count}", playersRegistered)
    .replace("{max}", championshipData.numberOfPlayers);

    return (
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
            <Block containerClassName="block-animation mb-4" childClassName="p-4">
              {isCreator ?
                <>
                  <h2 className="mb-2 h3">{language.championship.invitation.section.share_code.title.label}</h2>
                  <p className="text-gray" dangerouslySetInnerHTML={{__html: description}}></p>
                  <div className="justify-between mt-4 flex-v-centering">
                    <div>
                        <div className="font-bold text-primary">{language.championship.invitation.section.share_code.invitation_code.label}</div>
                        <div className="h2">{championshipData.invitationCode}</div>
                    </div>
                    <ShareButton championship={championshipData.name} creator={championshipData.createdBy.pseudo} code={championshipData.invitationCode} />
                  </div>
                </>
                :
                <p className="text-gray">{language.championship.invitation.section.waiting}</p>
              }
            </Block>

          <Block containerClassName="block-animation" childClassName="overflow-hidden">
            <h2 className="h3 flex-v-centering justify-between p-4" dangerouslySetInnerHTML={{__html: players}}></h2>
            {championshipData.players.map((player: { uuid: String; name: String; user: ChampionshipPlayerUserArray }) => (
              <Player key={String(player.uuid)} name={player.name} user={player.user} carClassName="w-16 h-10" />
            ))}
          </Block>

          {isCreator &&
            <>
              <Block containerClassName="mt-4 block-animation" childClassName="p-4 overflow-hidden">
                <h2 className="justify-between mb-2 h3">{language.championship.invitation.section.start.title.label}</h2>
                <p className="text-gray">{language.championship.invitation.section.start.description.label}</p>
                <StartChampionshipButton disabledManualLaunch={disabledManualLaunch} uuid={championshipData.uuid} />
              </Block>
              <Block containerClassName="mt-4 block-animation" childClassName="p-4 overflow-hidden">
                <CancelChampionshipButton uuid={championshipData.uuid} />
              </Block>
            </>
          }
        </Container>
      </main>
    );

  } catch (e: any) {
    if(e.message === "silly_season_started") {
      redirect(CHAMPIONSHIP_SILLYSEASON_PAGE(uuid));
    }
    redirect(NOTFOUND_PAGE);
  }
}
