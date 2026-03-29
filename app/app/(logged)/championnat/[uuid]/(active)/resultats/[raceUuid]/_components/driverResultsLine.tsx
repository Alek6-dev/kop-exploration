import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { ChampionshipResultsStrategyDriverArray, ChampionshipResultsStrategyTeamArray } from "../page";
import Image from "next/image";
import language from "@/messages/fr";
import { cn } from "@/lib/utils";
import IconSmallArrow from "@/public/assets/icons/small/small-arrow.svg"
import getClassBonusImpact from "@/utils/getClassBonusImpact";

interface DriverResultsLineProps {
    data: ChampionshipResultsStrategyDriverArray,
    selectedDriver: string | null,
    hasSprintResults: boolean,
    className?: string,
}

const DriverResultsLine = ({ data, selectedDriver, hasSprintResults, className } : DriverResultsLineProps) => {

    //console.log("driver ressource : ",data.driverResource.uuid)
    let isDriver1 = false;
    let color: string = "#000000";
    let avatar: string = "/assets/images/avatar/avatar-generic@2x.jpg";

    isDriver1 = selectedDriver === data.driverResource.uuid ? true : false;
    color = data.driverResource.team.color;
    avatar = process.env.NEXT_PUBLIC_API_URL+"/"+data.driverResource.image;

    let impactOnScore: string = "primary";
    if(isDriver1) {
        if(data.scoreWithBonus / data.score > 2) {
            impactOnScore = "impact-positive"
        }
        if(data.scoreWithBonus / data.score < 2) {
            impactOnScore = "impact-negative"
        }
    } else {
        if(data.scoreWithBonus > data.score) {
            impactOnScore = "impact-positive"
        }
        if(data.scoreWithBonus < data.score) {
            impactOnScore = "impact-negative"
        }
    }

    return (
        <TableRow className={cn("border-0 text-base", className)}>
            <TableCell className="w-6 py-1 !pl-0">
                <div className="relative">
                    <div className="rounded-full w-6 h-6 block overflow-hidden relative gradient-avatar-driver" style={{backgroundColor: color}}>
                        <Image src={avatar} alt="" quality={100} width={30} height={30} />
                    </div>
                    {isDriver1 && (
                        <div className="rounded-full w-3 h-3 text-black bg-gray absolute -right-[3px] bottom-0 text-[9px] font-bold flex-centering">1</div>
                    )}
                </div>
            </TableCell>
            <TableCell><b className="line-clamp-1 break-all">{data.driverResource.lastName}</b></TableCell>
            <TableCell className={getClassBonusImpact(data.reference.qualificationPositionPoint, data.qualificationPositionPoint)}>
                {data.qualificationPositionPoint}
            </TableCell>
            <TableCell className={getClassBonusImpact(data.reference.racePositionPoint, data.racePositionPoint)}>
                {data.racePositionPoint}
            </TableCell>
            {hasSprintResults &&
                <TableCell className={getClassBonusImpact(data.reference.sprintPositionPoint, data.sprintPositionPoint)}>
                    {data.sprintPositionPoint}
                </TableCell>
            }
            <TableCell className={getClassBonusImpact(data.reference.positionGain, data.positionGain)}>
                {data.positionGain}
            </TableCell>
            <TableCell className={getClassBonusImpact(data.reference.score, data.score)}>
                {data.score}
            </TableCell>
            <TableCell className={`font-bold ${impactOnScore}`}>
                {data.scoreWithBonus}
            </TableCell>
        </TableRow>
    )
}

export { DriverResultsLine }
