
import React from 'react';
import Image from "next/image";
import language from '@/messages/fr';

export interface MyTeamItemProps {
    image?: string,
    type: string,
    name?: string,
    color?: string,
    className ?: string,
}

const MyTeamItem = ({ ...props }: MyTeamItemProps ) => {
    let image, type, name, color;

    if(props.type == "driver") {
        props.image ? image = `${process.env.NEXT_PUBLIC_API_URL + '/' + props.image}` : image = "/assets/images/driver/driver-generic.svg";
        type = language.championship.sillyseason.myteam.driver
    }
    if(props.type == "team") {
        props.image ? image = `${process.env.NEXT_PUBLIC_API_URL + '/' + props.image}` : image = "/assets/images/team/team-generic.svg";
        type = language.championship.sillyseason.myteam.team
    }

    name = props.name ? props.name : language.championship.sillyseason.myteam.undefined;
    color = props.color ? props.color : "rgba(255, 255, 255, .06)";

    return (
        <div className="flex flex-col items-center shrink-0">
            <div className={"h-16 w-full min-w-12 max-w-18 rounded-lg overflow-hidden mb-2 flex items-end " + (props.color ? "gradient-avatar-"+props.type : "")} style={{ backgroundColor: color }}>
                <Image
                    src={image ?? ""}
                    alt=""
                    quality={100}
                    width={60}
                    height={60}
                    className="block h-full w-full object-contain object-bottom relative"
                />
            </div>
            <div className="text-xs text-primary leading-none"><b>{type}</b></div>
            <b className="text-center leading-[1.2] mt-[4px]">{name}</b>
        </div>
    )
}

export { MyTeamItem };
