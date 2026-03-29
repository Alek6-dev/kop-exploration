"use client";

import { startChampionship_action } from "@/actions/championship/startChampionship-action";
import { Button } from "@/components/ui/button";
import { toast } from "@/components/ui/use-toast";
import { CHAMPIONSHIP_LISTING_PAGE, CHAMPIONSHIP_SILLYSEASON_PAGE } from "@/constants/routing";
import language from "@/messages/fr";
import { useRouter } from "next/navigation";

interface StartChampionshipButtonProps {
    uuid: string;
    disabledManualLaunch: boolean;
}

const StartChampionshipButton = ({uuid, disabledManualLaunch} : StartChampionshipButtonProps) => {

    const router = useRouter();

    async function startChampionship(event: React.MouseEvent<HTMLButtonElement, MouseEvent>): Promise<void> {
        event.preventDefault();
        const resString: string = await startChampionship_action(uuid);
        const res = JSON.parse(resString);

        if (res.status === 1) {
            toast({
                title: res.message,
            });
            router.push(CHAMPIONSHIP_SILLYSEASON_PAGE(uuid));
            return;
        }
        toast({
            title: res.message,
            variant: "destructive",
        });
    }

    return(
        <Button className="mt-4" disabled={disabledManualLaunch} onClick={startChampionship}>{language.championship.invitation.section.start.button.label}</Button>
    )
}

export { StartChampionshipButton };
