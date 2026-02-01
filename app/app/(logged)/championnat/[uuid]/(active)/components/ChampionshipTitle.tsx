"use client"

import { Container } from "@/components/custom/container";
import language from "@/messages/fr";
import { TabsAsPage } from "@/components/custom/tabsAsPage";
import { CHAMPIONSHIP_RANKING_PAGE, CHAMPIONSHIP_RESULTS_PAGE, CHAMPIONSHIP_STRATEGY_PAGE } from "@/constants/routing";

export interface ChampionshipTitleProps {
    name: string,
    status: number,
    uuid: string,
    raceIndex: number;
    raceUuid: string;
    ranking?: number | null;
}

const ChampionshipTitle = ({ name, status, uuid, raceIndex, raceUuid, ranking }: ChampionshipTitleProps) => {
    const tabStrategy = {
        label: language.championship.race.tabs.strategy,
        url: CHAMPIONSHIP_STRATEGY_PAGE(uuid),
        id: "tab-strategy",
    }
    let tabs = [
        {
            label: language.championship.race.tabs.results,
            url: CHAMPIONSHIP_RESULTS_PAGE(uuid, raceUuid),
            id: "tab-results",
            // counter: 2
        },
        {
            label: language.championship.race.tabs.ranking,
            url: CHAMPIONSHIP_RANKING_PAGE(uuid),
            id: "tab-ranking",
            counter: ranking
        }
    ]

    if(status < 8) {
        tabs.unshift(tabStrategy);
    }

    // Translations with variables
    let title = language.championship.race.title
    .replace("{championship_name}", name)
    .replace("{race_number}", String(raceIndex+1));

    if (status === 8) {
        title = language.championship.race.titleOver
        .replace("{championship_name}", name)
    }

    return(
        <>
            <Container>
                <h1
                className="h1"
                dangerouslySetInnerHTML={{
                    __html: title,
                }}
                ></h1>
        </Container>
        <TabsAsPage tabs={tabs} defaultActive={tabs[0].id} className="mt-2 sticky top-0 z-10 bg-black no-delay" />
      </>
    )
}

export { ChampionshipTitle };
