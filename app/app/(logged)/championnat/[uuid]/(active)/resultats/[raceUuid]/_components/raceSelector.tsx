"use client"

import IconDropdownArrow from "@/public/assets/icons/small/dropdown-arrow.svg";
import Image from "next/image";
import language from "@/messages/fr";
import Link from "next/link";
import { Counter } from "@/components/custom/counter";
import { useState } from "react";
import { ChampionshipActiveRacesArray } from "@/type/championship";
import { CHAMPIONSHIP_RESULTS_PAGE } from "@/constants/routing";

interface raceSelectorProps {
    selectedRace: ChampionshipActiveRacesArray;
    races: ChampionshipActiveRacesArray[];
    championshipUuid: string;
}

const RaceSelector = ({ selectedRace, races, championshipUuid } : raceSelectorProps) => {
    const convertDate = (date: any) => {
        const dateString = date.toLocaleString("fr-FR", {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
        return (dateString);
    }

    const raceDate = new Date(selectedRace.date);
    let raceDateString = convertDate(raceDate);
    raceDateString = raceDateString.charAt(0).toUpperCase() + raceDateString.slice(1);

    const raceIndex: number = races.findIndex((item: ChampionshipActiveRacesArray) => item.uuid === selectedRace.uuid);

    const [displaySelectRace, setDisplaySelectRace] = useState(false);

    return (
        <div className="relative z-10">
            <button className="w-full border border-white-6 bg-[#000000]/15 h-auto flex items-center px-4 py-3 rounded-[10px]" onClick={() => setDisplaySelectRace(!displaySelectRace)}>
                <Image
                    src={`${process.env.NEXT_PUBLIC_API_URL}${selectedRace.flagUrl}`}
                    alt=""
                    quality={100}
                    width={40}
                    height={30}
                    className="mr-4"
                />
                <div className="text-left">
                    <h2 className="h3">{language.championship.results.race} {raceIndex + 1}</h2>
                    <p>{selectedRace.name}, {raceDateString}</p>
                </div>
                <div className="ml-auto self-center h-8 w-8 border rounded-[10px] border-white/10 flex-centering">
                    <IconDropdownArrow />
                </div>
            </button>

            <div className={"w-full flex flex-col absolute top-15 left-0 bg-black h-auto border border-white-6 shadow-[0_50px_100px_0_rgba(0,0,0,0.8)] rounded-[10px] overflow-hidden " + (displaySelectRace ? "" : "hidden")}>
                {races.map((race: ChampionshipActiveRacesArray, index: number) => (
                    <Link
                        key={race.uuid}
                        href={CHAMPIONSHIP_RESULTS_PAGE(championshipUuid, race.uuid)}
                        className={"flex w-full px-4 py-2 border-b border-white/10 bg-[#000000]/15 items-center" + (index + 1 === races.length ? ' border-b-0' : '') +  (race.status != 5 ? ' opacity-35 pointer-events-none' : '')}
                    >
                        <Counter counterValue={index + 1} className="bg-gray text-black mr-2" /> {race.name}
                    </Link>
                ))}
            </div>
        </div>
    )
}

export { RaceSelector }
