"use client"

import Image from "next/image";
import language from "@/messages/fr";

interface SeasonDriverCardProps {
    driverUuid: string;
    driverName: string;
    teamName: string | null;
    driverImage: string | null;
    maxUsages: number;
    usagesLeft: number;
    isSelected: boolean;
    isDisabled: boolean;
    badge: string;
    onSelect: () => void;
}

const SeasonDriverCard = ({ driverUuid, driverName, teamName, driverImage, maxUsages, usagesLeft, isSelected, isDisabled, badge, onSelect }: SeasonDriverCardProps) => {
    return (
        <div className="custom-radio-item custom-radio-item-driver" style={{ flex: 'none', minWidth: '38%' }}>
            <input
                type="radio"
                id={`season-driver-${driverUuid}-${badge}`}
                checked={isSelected}
                disabled={isDisabled}
                onChange={() => !isDisabled && onSelect()}
            />
            <label
                htmlFor={`season-driver-${driverUuid}-${badge}`}
                className="font-normal text-center flex-col gap-1 px-2 pt-3 pb-3 mb-0 !h-auto !rounded-lg cursor-pointer"
                onClick={(e) => {
                    if (isSelected && !isDisabled) {
                        e.preventDefault();
                        onSelect();
                    }
                }}
            >
                <b className="text-ellipsis overflow-hidden whitespace-nowrap w-full uppercase">{driverName}</b>
                <span className="text-white text-sm">{teamName}</span>
                <Image
                    src={driverImage ? `${process.env.NEXT_PUBLIC_API_URL + "/" + driverImage}` : "/assets/images/driver/driver-generic.svg"}
                    alt=""
                    quality={100}
                    width={66}
                    height={66}
                    className="block my-1"
                />
                <span className="text-sm text-gray mb-1">{usagesLeft} {language.championship.race.usage}{usagesLeft > 1 && "s"} {language.championship.race.remaining}{usagesLeft > 1 && "s"}</span>
                <div className="usage-bar h-[4px] overflow-hidden rounded-full flex" style={{ width: maxUsages * 10 - 2 + "px" }}>
                    {[...Array(maxUsages)].map((_, i) =>
                        <div key={i} className={"w-[8px] shrink-0 h-full -skew-x-[30deg] mr-[2px] " + (i < usagesLeft ? "bg-primary" : "bg-gray")}></div>
                    )}
                </div>
            </label>
        </div>
    );
};

export { SeasonDriverCard };
