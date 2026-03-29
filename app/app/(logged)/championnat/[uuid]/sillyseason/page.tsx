import { getSession } from "@/lib/security";
import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { CHAMPIONSHIP_LISTING_PAGE, CHAMPIONSHIP_SILLYSEASON_RESULTS_PAGE } from "@/constants/routing";
import Image from "next/image";
import { BettingRoundForm } from "./_components/BettingRoundForm";
import { Popin } from "@/components/custom/popin";
import { Button } from "@/components/ui/button";
import { PopinOpener } from "@/components/custom/popinOpener";
import { cookies } from "next/headers";
import { RoundGlobalInfo } from "./_components/RoundGlobalInfo";
import { MyTeam } from "./_components/MyTeam";
import Link from "next/link";

export default async function SillySeason(
  { params : { uuid }}: { params: { uuid: string }}
) {
  const token = cookies().get("session")?.value;
  const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }
  const headersWithTimezone = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
    "Timezone": timeZone,
  }

  const fetchData = async () => {
    try {
      const responsesJSON = await Promise.all([
        fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}`, { headers: headersWithTimezone }),
        fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/drivers-available`, { headers }),
        fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/teams-available`, { headers }),
        fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/my-player`, { headers }),
      ]);
      const [championshipData, driversData, teamsData, playerData] = await Promise.all(responsesJSON.map(r => r.json()));
      return [championshipData, driversData, teamsData, playerData];
    } catch (err) {
      throw err;
    }
  };

  const dataArray = await fetchData();
  const championshipData = dataArray[0];
  const driversData = dataArray[1];
  const teamsData = dataArray[2];
  const playerData = dataArray[3];

  //console.log("championship data: ", championshipData.players[0]);

  //console.log("player 1 : ", championshipData.players[0].bettingRounds[0]);
  //console.log("player 2 : ", championshipData.players[1].bettingRounds[0]);

  // Count total players participating in this round
  let playersBettingThisRound = 0;
  championshipData.players.forEach((player: any) => {
    if(player.bettingRoundDriver1Won == undefined || player.bettingRoundDriver2Won == undefined || player.bettingRoundTeamWon == undefined) {
      playersBettingThisRound++;
    }
  });

  // Manage remaining items selectable by the player
  let remainingDrivers = 2;
  let remainingTeam = 1;
  if(playerData.selectedDriver1) { remainingDrivers--; }
  if(playerData.selectedDriver2) { remainingDrivers--; }
  if(playerData.selectedTeam) { remainingTeam--; }
  // console.log("remainingDrivers", remainingDrivers);
  // console.log("remainingTeam", remainingTeam);

  // Selected items by the player (items already won by the player)
  const selectedDriver1 = playerData.selectedDriver1 != null ? {name: playerData.selectedDriver1.name, image: playerData.selectedDriver1.image, color: playerData.selectedDriver1.color} : null;
  const selectedDriver2 = playerData.selectedDriver2 != null ? {name: playerData.selectedDriver2.name, image: playerData.selectedDriver2.image, color: playerData.selectedDriver2.color} : null;
  const selectedTeam = playerData.selectedTeam != null ? {name: playerData.selectedTeam.name, image: playerData.selectedTeam.image, color: playerData.selectedTeam.color} : null;

  // Translations with variables
  const title = language.championship.sillyseason.title
  .replace("{championship_name}", championshipData.name);

  // Variables of this round
  const bettingRound = championshipData.currentRound;
  const bidSubmitted = playerData.bettingRounds[bettingRound - 1] != null ? true : false;

  const countPlayersWithBidOnCurrentRound = championshipData.countPlayersWithBidOnCurrentRound;
  const playerRoundBids = playerData.bettingRounds[bettingRound - 1];
  //console.log("player data betting rounds : ", playerData.bettingRounds)
  //console.log("player round bids : ", playerRoundBids)

  // Manage date format
  const convertDate = (date: any) => {
    const dateString = date.toLocaleString("fr-FR", {
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
    });
    return (dateString);
  }
  const roundEndDate = new Date(championshipData.currentRoundEndDate);
  const roundEndDateString = convertDate(roundEndDate);

  const getTotalTimeRemaining = (e: any) => {
    const total = Date.parse(e) - Date.parse(new Date().toString());
    return {
      total
    };
  };

  const timeRemaining = getTotalTimeRemaining(roundEndDate);

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

      {championshipData.status === 6 ?
        <Container className="mt-6">
          <Block containerClassName="block-animation mb-6 p-4">
            <h3 className="h3 text-red mb-2">{language.championship.cancelled.not_enough_races.title}</h3>
            <p>{language.championship.cancelled.not_enough_races.description}</p>
            <Link href={CHAMPIONSHIP_LISTING_PAGE} className="link self-start mt-2">{language.championship.cancelled.not_enough_races.link}</Link>
          </Block>
        </Container>
      :
      <>
        <Container className="mt-6">
          <Block containerClassName="block-animation mb-6">
            <div className="flex items-baseline justify-between p-4">
              <h2 className="h3">{language.championship.sillyseason.myteam.title}</h2>
              <div className="text-primary font-bold flex items-center">
                {language.championship.sillyseason.myteam.budget} {playerData.remainingBudget}
                <Image
                  src="/assets/icons/money/m.svg"
                  alt=""
                  quality={100}
                  width={24}
                  height={24}
                  className="ml-1"
                />
              </div>
            </div>
            <MyTeam bettingRound={bettingRound} selectedDriver1={selectedDriver1} selectedDriver2={selectedDriver2} selectedTeam={selectedTeam} />
          </Block>

          <RoundGlobalInfo uuid={uuid} bettingRound={bettingRound} roundEndDate={roundEndDate} timeRemaining={timeRemaining.total} roundEndDateString={roundEndDateString} countPlayersWithBidOnCurrentRound={countPlayersWithBidOnCurrentRound} playersBettingThisRound={playersBettingThisRound} status={championshipData.status} />

          <BettingRoundForm drivers={driversData} teams={teamsData} remainingBudget={playerData.remainingBudget} remainingTeam={remainingTeam} remainingDrivers={remainingDrivers} bidSubmitted={bidSubmitted} playerRoundBids={playerRoundBids} championshipUuid={uuid} />

        </Container>

        <Popin title={language.championship.sillyseason.popin.title} id="popin">
          <p>{language.championship.sillyseason.popin.p1}</p>
          <p>{language.championship.sillyseason.popin.p2}</p>
          <p>{language.championship.sillyseason.popin.p3}</p>
          <p>{language.championship.sillyseason.popin.p4}</p>
          <p><b>{language.championship.sillyseason.popin.p5}</b></p>
          <p>{language.championship.sillyseason.popin.p6}</p>
        </Popin>
      </>
      }
    </main>
  )
}
