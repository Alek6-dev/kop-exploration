"use client"

import { Tabs } from "@/components/custom/tabs";
import { useState } from "react";
import { Container } from "@/components/custom/container";
import language from "@/messages/fr";
import Image from "next/image";
import { Button } from "@/components/ui/button";
import { Block } from "@/components/custom/block";
import { Separator } from "@/components/custom/separator";
import { BidItem } from "./BidItem";
import { useToast } from "@/components/ui/use-toast";
import { addBids_action } from "@/actions/championship/addBids-action";
import { useRouter } from "next/navigation";
import { ChampionshipActivePlayerDriversArray, ChampionshipPlayerDriversArray, ChampionshipPlayerTeamArray } from "@/type/championship";

export interface bettingRoundDriverArray {
    uuid: string,
    driver: ChampionshipActivePlayerDriversArray,
    bidAmount: number,
}

export interface bettingRoundTeamArray {
    uuid: string,
    team: ChampionshipPlayerTeamArray,
    bidAmount: number,
}

interface playerRoundBids {
    uuid: string,
    round: number,
    isSetBySystem: boolean,
    bettingRoundTeam: string,
    bettingRoundDrivers: bettingRoundDriverArray[],
}

export interface BettingRoundContentProps {
    drivers: ChampionshipPlayerDriversArray[],
    teams: ChampionshipPlayerTeamArray[],
    remainingBudget: number,
    remainingTeam: number,
    remainingDrivers: number,
    bidSubmitted: boolean,
    playerRoundBids: any|null;
    championshipUuid: string,
}


const BettingRoundForm = ({ drivers, teams, remainingBudget, remainingTeam, remainingDrivers, bidSubmitted, playerRoundBids=null, championshipUuid }: BettingRoundContentProps) => {
    const { toast } = useToast();
    const router = useRouter();
    const [isLoading, setIsLoading] = useState(false);

    //console.log("betting round drivers:" , drivers);

    const handleSubmit = async () => {
        setIsLoading(true);
        const data = {
            driver1Uuid: remainingDrivers > 0 ? bidValues[0].uuid : null,
            driver1BidAmount: remainingDrivers > 0 ? Number(bidValues[0].value) : null,
            driver2Uuid: remainingDrivers > 1 ? bidValues[1].uuid : null,
            driver2BidAmount: remainingDrivers > 1 ? Number(bidValues[1].value) : null,
            teamUuid: remainingTeam > 0 ? bidValues[2].uuid : null,
            teamBidAmount: remainingTeam > 0 ? Number(bidValues[2].value) : null,
        }

        const resString: string = await addBids_action(data, championshipUuid);
        const res = JSON.parse(resString);

        if (res.status === 1) {
            toast({
                title: res.message,
            });
            router.refresh();
        } else {
            toast({
                title: res.message,
                variant: "destructive",
            });
            setIsLoading(false);
        }
    };

    const bidValuesArray = [
        {
            name: "driver1Bid",
            value: "",
            uuid: "",
        },
        {
            name: "driver2Bid",
            value: "",
            uuid: "",
        },
        {
            name: "teamBid",
            value: "",
            uuid: "",
        }
    ];

    const [bidValues, setBidValues] = useState(bidValuesArray);
    const [driversSelected, setDriversSelected] = useState([]);
    const [teamSelected, setTeamSelected] = useState([]);
    const [roundBudget, setRoundBudget] = useState(remainingBudget);
    // console.log("bid values", bidValues);
    // console.log("drivers selected", driversSelected);
    // console.log("team selected", teamSelected);

    // Get values from playerRoundBids if they exist (player submitted a bid(s)) or set them to null
    let bettingRoundDrivers = null;
    if(playerRoundBids != null && playerRoundBids.bettingRoundDrivers != undefined) {
        bettingRoundDrivers = playerRoundBids.bettingRoundDrivers;
    }
    let bettingRoundTeam = null;
    if(playerRoundBids != null && playerRoundBids.bettingRoundTeams != undefined) {
        bettingRoundTeam = playerRoundBids.bettingRoundTeams;
    }
    //console.log("bettingRoundDrivers : ", bettingRoundDrivers);
    //("bettingRoundTeam : ", bettingRoundTeam);

    // Manage input value update
    const handleInput = (uuid: string, value: string, type: "driver"|"team") => {
        // Get values and matching uuid on change
        const newBidValue = value;
        const newUuid = uuid;
        // Update state with new values
        if(type == "driver") {
            if(bidValues[0].value === "" || bidValues[0].uuid == uuid) {
                updateBidValues(0, newBidValue, newUuid);
            }
            else if(bidValues[1].value === "" || bidValues[1].uuid == uuid) {
                updateBidValues(1, newBidValue, newUuid);
            }
        } else if(type == "team") {
            if(bidValues[2].value === "" || bidValues[2].uuid == uuid) {
                updateBidValues(2, newBidValue, newUuid);
            }
        }
        // Update remaining budget
        setRoundBudget(remainingBudget - Number(bidValues[0].value) - Number(bidValues[1].value) - Number(bidValues[2].value));
    }

    // Manage input min value (replace value by minimum if value is less than minimum)
    const handleInputMinValue = (uuid: string, input: HTMLInputElement, type: "driver"|"team") => {
        input.addEventListener("blur", () => {
            if(Number(input.value) < Number(input.min)) {
                input.value = input.min;
            }
            handleInput(uuid, input.value, type);
        });
    }

    // Manage input max value (avoid 4 digits values)
    const handleInputTyping = (uuid: string, input: HTMLInputElement, type: "driver"|"team") => {
        input.addEventListener("input", () => {
            if(Number(input.value) > Number(input.max)) {
                input.value = input.value.slice(0, 3);
            }
            handleInput(uuid, input.value, type);
        });
    }

    // On checkbox click, manage input visibility and values
    const handleCheckboxClick = (e:any, uuid: string, type: "driver"|"team") => {
        const input = document.getElementById(uuid) as HTMLInputElement;
        // If input is hidden, show it
        if (input?.classList.contains("hidden")) {
            // If driver is selected and there is less than selectable drivers selected, show input
            if(type == "driver" && driversSelected.length < remainingDrivers) {
                input.classList.remove("hidden");
                input.value = input.min;
                handleInput(uuid, input.value, "driver");
                // Update drivers selected state with ID of the checkbox
                setDriversSelected(driversSelected.concat(e.target.id));
                handleInputTyping(uuid, input, "driver");
                handleInputMinValue(uuid, input, "driver");
            }
            // If team is selected and there is less than selectable team selected, show input
            else if (type == "team" && teamSelected.length < remainingTeam) {
                input.classList.remove("hidden");
                input.value = input.min;
                handleInput(uuid, input.value, "team");
                // Update team selected state with ID of the checkbox
                setTeamSelected(teamSelected.concat(e.target.id));
                handleInputTyping(uuid, input, "team");
                handleInputMinValue(uuid, input, "team");
            }
        } else {
            // hide input, remove value entered by the user and
            input.classList.add("hidden");
            input.value = "";
            if(type == "driver") {
                // Remove checkbox ID from drivers selected state
                setDriversSelected(drivers => drivers.filter(driver => driver !== e.target.id));
                // Remove values from bid values state if checkbox is unchecked
                if(bidValues[0].uuid === uuid) {
                    updateBidValues(0, "", "");
                }
                else if(bidValues[1].uuid === uuid) {
                    updateBidValues(1, "", "");
                }
            } else {
                // Remove checkbox ID from team selected state
                setTeamSelected(teams => teams.filter(team => team !== e.target.id));
                // Remove values from bid values state if checkbox is unchecked
                if(bidValues[2].uuid === uuid) {
                    updateBidValues(2, "", "");
                }
            }
            setRoundBudget(remainingBudget - Number(bidValues[0].value) - Number(bidValues[1].value) - Number(bidValues[2].value));
        }
    }

    // Update bid values state with new values
    const updateBidValues = (index: number, value: string, uuid: string) => {
        let newValuesArray = [...bidValues];
        newValuesArray[index].value = value;
        newValuesArray[index].uuid = uuid;
        setBidValues(newValuesArray);
    }

    // Tabs visibility and active state management
    const tabs = [];
    let defaultTabState = "tab-drivers";

    if(remainingDrivers == 0 && remainingTeam > 0) {
        defaultTabState = "tab-team";
    }

    let [tabState, setTabState] = useState(defaultTabState);
    const handleTabState = (newActiveState: string) => {
        setTabState(newActiveState);
    }

    if(remainingDrivers > 0 && remainingTeam > 0) {
        tabs.push({
            label: language.championship.sillyseason.tab.drivers.label,
            id: "tab-drivers"
        })
        tabs.push({
            label: language.championship.sillyseason.tab.teams.label,
            id: "tab-team"
        })
    }

    // Check if the player team is complete
    let teamComplete = false;
    if(remainingDrivers === 0 && remainingTeam === 0) {
        teamComplete = true;
    }

    // Translations with variables
    let buttonLabel = language.championship.sillyseason.button.validate.submit;
    let buttonDisabled = false;
    if(roundBudget < 0) {
        buttonLabel = language.championship.sillyseason.button.validate.negative_budget;
        buttonDisabled = true;
    }
    if((remainingDrivers > 0 && bidValues[0].value == "") || (remainingDrivers > 1 && bidValues[1].value == "") || (remainingTeam > 0 && bidValues[2].value == "")) {
        if(remainingDrivers > 0 && remainingTeam > 0) {
            buttonLabel = language.championship.sillyseason.button.validate.missing_items
            .replace("{countDriver}", String(remainingDrivers))
            .replace("{countTeam}", String(remainingTeam));
            (remainingDrivers > 1) ? buttonLabel = buttonLabel.replace("{s}", "s") : buttonLabel = buttonLabel.replace("{s}", "");
        } else if (remainingDrivers > 0 && remainingTeam == 0) {
            buttonLabel = language.championship.sillyseason.button.validate.missing_item_driver
            .replace("{countDriver}", String(remainingDrivers));
            (remainingDrivers > 1) ? buttonLabel = buttonLabel.replace("{s}", "s") : buttonLabel = buttonLabel.replace("{s}", "");
        } else if (remainingDrivers == 0 && remainingTeam > 0) {
            buttonLabel = language.championship.sillyseason.button.validate.missing_item_team
            .replace("{countTeam}", String(remainingTeam));
        }
        buttonDisabled = true;
    }

    let driversDescription = language.championship.sillyseason.tab.drivers.description;
    if(remainingDrivers < 2) {
        driversDescription = language.championship.sillyseason.tab.drivers.description_singular;
    }
    if(bidSubmitted) {
        driversDescription = language.championship.sillyseason.tab.drivers.description_submitted;
    }

    let teamDescription = language.championship.sillyseason.tab.teams.description;
    if(bidSubmitted) {
        teamDescription = language.championship.sillyseason.tab.teams.description_submitted;
    }

    return(
        <>
        {teamComplete ?
            <Block containerClassName="p-4 block-animation">
                <div dangerouslySetInnerHTML={{
                __html: language.championship.sillyseason.myteam.complete,
                }}></div>
            </Block>
        :
            <>
                <Block containerClassName={"block-animation " + (bidSubmitted ? "" : "mb-16")}>
                    <Tabs tabs={tabs} defaultActive={defaultTabState} className="block-animation no-delay tabs-sillyseason" changeState={handleTabState} />
                    {remainingDrivers > 0 &&
                        <Container className={"p-0 overflow-hidden rounded-lg " + (tabState === "tab-drivers" ? "" : "hidden")}>
                            <div className="p-4 pb-2 flex justify-between align-baseline">
                                <h3 className="h3">{language.championship.sillyseason.tab.drivers.label}</h3>
                                <span className="text-gray"><span id="selected-drivers">{driversSelected.length}</span>/{remainingDrivers}</span>
                            </div>
                            <p className="text-gray px-4 pb-4">{driversDescription}</p>

                            {Array.isArray(drivers) && drivers.map((driver: ChampionshipPlayerDriversArray) => (
                                <BidItem key={driver.uuid} item={driver} bidSubmitted={bidSubmitted} itemSelected={driversSelected} remainingItem={remainingDrivers} bettingRoundDrivers={bettingRoundDrivers} handleClick={handleCheckboxClick} />
                            ))}
                        </Container>
                    }

                    {remainingTeam > 0 &&
                        <Container className={"p-0 overflow-hidden rounded-lg " + (tabState === "tab-team" ? "" : "hidden")}>
                            <div className="p-4 pb-2 flex justify-between align-baseline">
                                <h3 className="h3">{language.championship.sillyseason.tab.teams.label}</h3>
                                <span className="text-gray"><span id="selected-teams">{teamSelected.length}</span>/{remainingTeam}</span>
                            </div>
                            <p className="text-gray px-4 pb-4">
                                {teamDescription}
                            </p>
                            {Array.isArray(teams) && teams.map((team: ChampionshipPlayerTeamArray) => (
                                <BidItem key={team.uuid} type="team" item={team} bidSubmitted={bidSubmitted} itemSelected={teamSelected} remainingItem={remainingTeam} bettingRoundTeam={bettingRoundTeam} handleClick={handleCheckboxClick} />
                            ))}
                        </Container>
                    }
                </Block>

                {bidSubmitted == false &&
                    <>
                    {teamComplete == false &&
                        <div className="fixed bottom-14 pb-2 pt-4 px-4 bg-gradient-to-t from-black from-85% w-full -ml-4">
                            <Button className="block-animation w-full mb-1 max-w-[740px]" type="submit" disabled={isLoading ? isLoading : buttonDisabled} onClick={handleSubmit}>
                                {buttonLabel}
                            </Button>
                            <div className="flex text-sm">
                                <span className={"flex " + (roundBudget < 0 ? "text-red": "")}>
                                {language.championship.sillyseason.bettingRound.remaining_budget} {roundBudget}
                                    <Image
                                        src="/assets/icons/money/m.svg"
                                        alt=""
                                        quality={100}
                                        width={16}
                                        height={16}
                                        className="ml-1"
                                    />
                                </span>
                                <span className="ml-auto flex items-center">
                                    {language.championship.sillyseason.bettingRound.driver} {driversSelected.length}/{remainingDrivers}
                                    <Separator className="separator-vertical bg-gray mx-2 h-[12px]" />
                                    {language.championship.sillyseason.bettingRound.team} {teamSelected.length}/{remainingTeam}
                                </span>
                            </div>
                        </div>
                    }
                    </>
                }
            </>
        }
        </>
    )
}

export { BettingRoundForm};
