"use client";

import { cancelChampionship_action } from "@/actions/championship/cancelChampionship-action";
import { toast } from "@/components/ui/use-toast";
import { CHAMPIONSHIP_LISTING_PAGE } from "@/constants/routing";
import language from "@/messages/fr";
import { useRouter } from "next/navigation";

const CancelChampionshipButton = ({uuid} : {uuid: string}) => {

    const router = useRouter();

    async function cancelChampionship(event: React.MouseEvent<HTMLLinkElement, MouseEvent>): Promise<void> {
        event.preventDefault();
        const resString: string = await cancelChampionship_action(uuid);
        const res = JSON.parse(resString);

        if (res.status === 1) {
            toast({
                title: res.message,
            });
            router.push(CHAMPIONSHIP_LISTING_PAGE);
            return;
        }
        toast({
            title: res.message,
            variant: "destructive",
        });
    }

    return(
        <span onClick={cancelChampionship} className="text-red self-center link">
            {language.championship.invitation.section.cancel.button.label}
        </span>
    )
}

export { CancelChampionshipButton };
