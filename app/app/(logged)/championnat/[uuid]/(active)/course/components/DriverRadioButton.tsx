"use client"

import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
  } from "@/components/ui/form";
import { RadioGroupItem } from "@/components/ui/radio-group";
import language from "@/messages/fr";
import Image from "next/image";

export interface dataArray {
    name: string,
    team: string,
    uuid: string,
    image: string,
    raceUsage: number,
    duelUsage: number
    driversMaxUsage: number,
}

export interface DriverRadioButtonProps {
    data: dataArray,
    type: string,
    defaultValue: string
}

const DriverRadioButton = ({ data, type, defaultValue}: DriverRadioButtonProps) => {
    const checked = defaultValue == data.uuid ? true : false;

    const remainingUsage = type === "gp" ? data.raceUsage : data.duelUsage;

    return(
        <FormItem className="custom-radio-item custom-radio-item-driver grow-0">
            <FormControl>
                <RadioGroupItem value={data.uuid} checked={checked} disabled={remainingUsage === 0} />
            </FormControl>
            <FormLabel className="font-normal text-center flex-col px-2 pb-3 mb-0 !h-auto !rounded-lg">
                <div className="rounded-full gradient-primary-white border border-white/25 shadow-[0_0_10px_0_rgba(241,196,69,0.46)] h-[24px] w-[24px] flex items-center justify-center mx-auto text-black font-bold -translate-y-1/2 -mb-1 selected-bubble">{type == "gp" ? "1" : "T"}</div>
                <b className="text-ellipsis overflow-hidden whitespace-nowrap w-full">{data.name}</b>
                <span className="text-white text-sm">{data.team}</span>
                <Image
                    src={`${process.env.NEXT_PUBLIC_API_URL + '/' + data.image}`}
                    alt=""
                    quality={100}
                    width={66}
                    height={66}
                    className="block my-1"
                />
                <span className="text-sm text-gray mb-1">{remainingUsage} {language.championship.race.usage}{remainingUsage > 1 && "s"} {language.championship.race.remaining}{remainingUsage > 1 && "s"}</span>
                <div className="usage-bar h-[4px] overflow-hidden rounded-full flex" style={{width: data.driversMaxUsage*10-2+"px"}}>
                    {[...Array(data.driversMaxUsage)].map((x, i) =>
                    <div key={i} className={"w-[8px] shrink-0 h-full -skew-x-[30deg] mr-[2px] " + (i < remainingUsage ? "bg-primary" : "bg-gray")}></div>
                    )}
                </div>
            </FormLabel>
        </FormItem>
    )
}

export { DriverRadioButton };
