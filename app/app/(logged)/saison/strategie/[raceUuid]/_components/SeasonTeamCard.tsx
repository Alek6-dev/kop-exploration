"use client";

import Image from "next/image";
import language from "@/messages/fr";

interface SeasonTeamCardProps {
  teamUuid: string;
  teamName: string;
  teamColor: string | null;
  teamImage: string | null;
  maxUsages: number;
  usagesLeft: number;
  isSelected: boolean;
  isDisabled: boolean;
  onSelect: () => void;
}

const SeasonTeamCard = ({
  teamUuid,
  teamName,
  teamColor,
  teamImage,
  maxUsages,
  usagesLeft,
  isSelected,
  isDisabled,
  onSelect,
}: SeasonTeamCardProps) => {
  return (
    <div className="custom-radio-item custom-radio-item-driver" style={{ flex: "1" }}>
      <input
        type="radio"
        id={`season-team-${teamUuid}`}
        checked={isSelected}
        disabled={isDisabled}
        onChange={() => !isDisabled && onSelect()}
      />
      <label
        htmlFor={`season-team-${teamUuid}`}
        className="font-normal text-center flex-col gap-1 px-2 pt-3 pb-3 mb-0 !h-auto min-h-[183px] !rounded-lg cursor-pointer"
        onClick={(e) => {
          if (isSelected && !isDisabled) {
            e.preventDefault();
            onSelect();
          }
        }}
      >
        <b className="text-ellipsis overflow-hidden whitespace-nowrap w-full uppercase">{teamName}</b>
        {teamImage ? (
          <Image
            src={`${process.env.NEXT_PUBLIC_API_URL}/${teamImage}`}
            alt={teamName}
            width={88}
            height={88}
            quality={100}
            className="block mt-1 object-contain"
          />
        ) : (
          <div
            className="w-[88px] h-[88px] rounded-full mx-auto mt-1"
            style={{ backgroundColor: teamColor ?? "#555" }}
          />
        )}
        <span className="text-sm text-gray mb-1">
          {usagesLeft} {language.championship.race.usage}{usagesLeft > 1 && "s"} {language.championship.race.remaining}{usagesLeft > 1 && "s"}
        </span>
        <div className="usage-bar h-[4px] overflow-hidden rounded-full flex" style={{ width: maxUsages * 10 - 2 + "px" }}>
          {[...Array(maxUsages)].map((_, i) => (
            <div key={i} className={"w-[8px] shrink-0 h-full -skew-x-[30deg] mr-[2px] " + (i < usagesLeft ? "bg-primary" : "bg-gray")} />
          ))}
        </div>
      </label>
    </div>
  );
};

export { SeasonTeamCard };
