import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { Separator } from "@/components/custom/separator";
import Image from "next/image";
import { Popin } from "@/components/custom/popin";
import { cookies } from "next/headers";
import { ChampionshipActiveRacesArray } from "@/type/championship";
import { Countdown } from "@/components/custom/countdown";
import { ChampionshipTitle } from "../components/ChampionshipTitle";
import { CHAMPIONSHIP_RANKING_PAGE, OTHER_USER_PROFILE_PAGE } from "@/constants/routing";
import { redirect } from "next/navigation";
import { StrategyForm } from "./components/StrategyForm";

export default async function RaceStrategy({params : { uuid }}: {params: { uuid: string }}) {
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
        fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships/${uuid}/my-player`, { headers }),
        fetch(`${process.env.NEXT_PUBLIC_REST_URL}/bonus?type=strategy&isJoker=0`, { headers }),
        fetch(`${process.env.NEXT_PUBLIC_REST_URL}/bonus?type=duel&isJoker=0`, { headers }),
      ]);
      const [championshipData, playerData, bonusGPData, bonusDuelData] = await Promise.all(responsesJSON.map(r => r.json()));
      return [championshipData, playerData, bonusGPData, bonusDuelData];
    } catch (err) {
      throw err;
    }
  };

  const dataArray = await fetchData();
  const championshipData = dataArray[0];
  const playerData = dataArray[1];
  const bonusGPData = dataArray[2];
  const bonusDuelData = dataArray[3];
  //console.log("championship data : ", championshipData);
  //console.log("players data : ", playerData);
  //console.log("bonus GP data : ", bonusGPData);
  //console.log("bonus duel data : ", bonusDuelData);

  // If the championship is over, we can't access strategy page anymore
  if(championshipData.status === 8) {
    redirect(CHAMPIONSHIP_RANKING_PAGE(uuid));
  }

  const convertDateHours = (date: any) => {
    const dateString = date.toLocaleString("fr-FR", {
      //timeZone: 'UTC',
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      //second: 'numeric',
    });
    return (dateString);
  }
  const convertDate = (date: any) => {
    const dateString = date.toLocaleString("fr-FR", {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
    return (dateString);
  }

  const raceIndex: number = championshipData.countRacesOver;
  const race: ChampionshipActiveRacesArray = championshipData.races[raceIndex];
  const previousRace: ChampionshipActiveRacesArray = championshipData.races[raceIndex - 1];
  const previousRaceUuid = previousRace ? previousRace.uuid : "no-results";

  const raceDate = new Date(race.date);
  let raceDateString = convertDate(raceDate);
  raceDateString = raceDateString.charAt(0).toUpperCase() + raceDateString.slice(1);

  const strategyEndDate = new Date(race.limitStrategyDate);
  const strategyEndDateString = convertDateHours(strategyEndDate);

  const getTotalTimeRemaining = (e: any) => {
    const total = Date.parse(e) - Date.parse(new Date().toString());
    return {
      total
    };
  };

  const timeRemaining = getTotalTimeRemaining(strategyEndDate);

  // Translations with variables
  const popingp_p3 = language.championship.race.gp.popin.p3
  .replaceAll("{M}", "<Image src='/assets/icons/money/m.svg' alt='' quality=100 width=16 height=16 class='ml-[3px] mr-[3px] inline-block -translate-y-[2px]'/>");

  return(
    <>
      <ChampionshipTitle name={championshipData.name} status={championshipData.status} uuid={uuid} raceIndex={raceIndex} raceUuid={previousRaceUuid} ranking={playerData.position} />
      <div className="overflow-hidden">
        <Container className="mt-6">
          <Block containerClassName="block-animation mb-4">
            <div className="flex justify-between p-4 pb-0">
              <div>
                <h2 className="h3">{race.name}</h2>
                <p>{raceDateString}</p>
              </div>
              <Image
                src={`${process.env.NEXT_PUBLIC_API_URL}${race.flagUrl}`}
                alt=""
                quality={100}
                width={40}
                height={30}
                className="ml-4"
              />
            </div>
            <div className="text-sm text-bold text-primary px-4 mt-[3px]">
              {race.status > 2 ? (
                <span className="text-red font-bold">{language.championship.race.strategy_time_passed}</span>
              ) : (
                <>
                  {language.championship.race.strategy_end}&nbsp;
                  <Countdown roundEndDate={strategyEndDate} timeRemaining={timeRemaining.total} isStrategyCountdown={true} />&nbsp;
                  {language.championship.race.strategy_end2} {strategyEndDateString})
                </>
              )}
              <Separator className="mt-3 bg-gradient-to-r from-white-6 to-black" />
            </div>
            <div className="flex items-center font-bold px-4 py-[12px]">
              {language.championship.race.race} {raceIndex + 1} / {championshipData.numberOfRaces} <Separator className="separator-vertical h-[12px] mx-2" /><span className="text-primary">{language.championship.race.budget} {playerData.remainingBudget}</span>
              <Image
                src="/assets/icons/money/m.svg"
                alt=""
                quality={100}
                width={16}
                height={16}
                className="ml-1"
              />
            </div>
            {playerData.user.carCosmetic &&
              <Image
                src={`${process.env.NEXT_PUBLIC_API_URL + '/' +  playerData.user.carCosmetic.image1}`}
                alt=""
                quality={100}
                width={124}
                height={70}
                className="absolute -right-8 bottom-2"
              />
            }
          </Block>

          <StrategyForm playerData={playerData} championshipDataUuid={championshipData.uuid} championshipPlayers={championshipData.players} raceStatus={race.status} bonusGP={bonusGPData} bonusDuel={bonusDuelData} />

        </Container>
      </div>

      <Popin title={language.championship.race.gp.popin.title} id="popin-gp">
        <h4 className="h4 mb-3 text-white">{language.championship.race.gp.popin.title1}</h4>
        <p>{language.championship.race.gp.popin.p1}</p>
        <h4 className="h4 mb-3 mt-6 text-white">{language.championship.race.gp.popin.title2}</h4>
        <p>{language.championship.race.gp.popin.p2}</p>
        <h4 className="h4 mb-3 mt-6 text-white">{language.championship.race.gp.popin.title3}</h4>
        <p dangerouslySetInnerHTML={{ __html: popingp_p3}}></p>
      </Popin>

      <Popin title={language.championship.race.duel.popin.title} id="popin-duel">
        <h4 className="h4 mb-3 text-white">{language.championship.race.duel.popin.title1}</h4>
        <p>{language.championship.race.duel.popin.p1}</p>
        <h4 className="h4 mb-3 mt-6 text-white">{language.championship.race.duel.popin.title2}</h4>
        <p>{language.championship.race.duel.popin.p2}</p>      </Popin>

      <Popin title={language.championship.race.bonus.popin_usage_title} id="popin-bonus-usage">
        <h4 className="h4 mb-3 text-white">{language.championship.race.bonus.combinable}</h4>
        <p dangerouslySetInnerHTML={{ __html: language.championship.race.bonus.combinable_explanation}}></p>
        <h4 className="h4 mb-3 mt-6 text-white">{language.championship.race.bonus.not_combinable}</h4>
        <p>{language.championship.race.bonus.not_combinable_explanation}</p>
      </Popin>
    </>
  )
}
