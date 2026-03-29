import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { ChampionshipResultsStrategyDriverArray, ChampionshipResultsStrategyTeamArray } from "../page";
import Image from "next/image";
import language from "@/messages/fr";
import { cn } from "@/lib/utils";
import getClassBonusImpact from "@/utils/getClassBonusImpact";

interface TeamResultsLineProps {
    data: ChampionshipResultsStrategyTeamArray,
    hasSprintResults: boolean,
    className?: string,
}

const TeamResultsLine = ({ data, hasSprintResults, className } : TeamResultsLineProps) => {

    let color: string = "#000000";
    let avatar: string = "/assets/images/avatar/avatar-generic@2x.jpg";

    color = data.teamResource.color;
    avatar = process.env.NEXT_PUBLIC_API_URL+"/"+data.teamResource.image;

    const teamColSpan = hasSprintResults ? 6 : 5;

    // console.log("data in TeamResultsLine : ", data);

    return (
        <TableRow className={cn("border-0 text-base", className)}>
            <TableCell className="w-6 py-1 !pl-0">
                <div className="relative">
                    <div className="rounded-full w-6 h-6 block overflow-hidden relative gradient-avatar-team" style={{backgroundColor: color}}>
                        <Image src={avatar} alt="" quality={100} width={30} height={30} />
                    </div>
                </div>
            </TableCell>
            <TableCell><b className="line-clamp-1 break-all">{data.teamResource.name}</b></TableCell>
            <TableCell colSpan={teamColSpan} className="text-gray">
                {language.championship.results.multiplicator}
                <span className={`text-primary font-bold ml-1 ${getClassBonusImpact(data.reference.teamMultiplier, data.teamMultiplier)}`}>{data.teamMultiplier / 10}</span>
            </TableCell>
        </TableRow>
    )
}

export { TeamResultsLine }
