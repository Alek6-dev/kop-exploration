"use client"

import { Block } from "@/components/custom/block"
import { PopinOpener } from "@/components/custom/popinOpener"
import { OTHER_USER_PROFILE_PAGE } from "@/constants/routing"
import { ChampionshipActivePlayerArray } from "@/type/championship"
import { SelectDriverForm } from "./SelectDriverForm"
import language from "@/messages/fr"
import { CarInListing } from "@/components/custom/carInListing"
import Image from "next/image";
import { useState } from "react"
import { Button } from "@/components/ui/button"
import { selectDriver_action } from "@/actions/championship/selectDriver-action"
import { toast, useToast } from "@/components/ui/use-toast"
import { Popin } from "@/components/custom/popin"
import { Carousel, CarouselContent, CarouselItem } from "@/components/ui/carousel"
import { StrategyCard } from "@/components/custom/strategyCard"
import { BonusArray } from "@/type/bonus"
import { SelectBonus } from "./SelectBonus"
import { SelectBonusDataType, selectBonus_action } from "@/actions/championship/selectBonus-action"
import { UnselectBonusDataType, unselectBonus_action } from "@/actions/championship/unselectBonus-action"

export interface StrategyFormProps {
    playerData: ChampionshipActivePlayerArray,
    championshipDataUuid: string,
    championshipPlayers: Array<ChampionshipActivePlayerArray>,
    raceStatus: number,
    bonusGP: Array<BonusArray>,
    bonusDuel: Array<BonusArray>,
}

export interface SelectedStrategyArray {
    driverGP: string,
    driverDuel: string,
    bonusGP: string,
    bonusGPTarget: string,
    bonusDuel: string,
}

const StrategyForm = ({ playerData, championshipDataUuid, championshipPlayers, raceStatus, bonusGP, bonusDuel }: StrategyFormProps) => {
    const { toast } = useToast();

    const savedStrategy: SelectedStrategyArray = {
        driverGP: playerData.currentStrategy?.driver ? playerData.currentStrategy.driver.uuid : "",
        driverDuel: playerData.currentDuel?.driver ? playerData.currentDuel.driver.uuid : "",
        bonusGP: playerData.currentStrategy?.bonusApplication ? playerData.currentStrategy.bonusApplication.bonus.uuid : "",
        bonusGPTarget: playerData.currentStrategy?.bonusApplication ? playerData.currentStrategy.bonusApplication.target.uuid : "",
        bonusDuel: playerData.currentDuel?.bonusApplication ? playerData.currentDuel.bonusApplication.bonus.uuid : "",
    }

    //console.log("initial saved strategy :", savedStrategy);

    // Manage visibility of submit button (info : it must be enabled only when both drivers are selected, if there's a change in user selection and also, if a GP Bonus with target is selected, the target must be selected)
    const [isStrategyComplete, setIsStrategyComplete] = useState(false);

    // Manage submit button default label on page load (or there's nothing to save or the two drivers are not selected yet)
    const submitButtonDefaultLabel = ((savedStrategy.driverGP !== "") && (savedStrategy.driverDuel !== "")) ? language.championship.race.cta.no_save_required : language.championship.race.cta.save_disabled;
    const [submitButtonlabel, setSubmitButtonLabel] = useState(submitButtonDefaultLabel);

    // Manage global strategy (use to submit the data to the API)
    const [strategy, setStrategy] = useState(savedStrategy);

    // Manage selected bonus item infos (use to display the selected bonus on the page)
    const selectedGPBonusDefaultValue = bonusGP.find(bonus => bonus.uuid === savedStrategy.bonusGP) || undefined;
    const selectedDuelBonusDefaultValue = bonusDuel.find(bonus => bonus.uuid === savedStrategy.bonusDuel) || undefined;
    const [selectedGPBonus, setSelectGPBonus] = useState<BonusArray | undefined>(selectedGPBonusDefaultValue);
    const [selectedDuelBonus, setSelectedDuelBonus] = useState<BonusArray | undefined>(selectedDuelBonusDefaultValue);

    // Update strategy
    const handleStrategy = (newStrategy: any) => {
        //console.log("strategy form - selected strategy", newStrategy);
        setStrategy(newStrategy);

        // Initial condition : we can submit strategy only if the two drivers are selected
        if(newStrategy.driverGP !== "" && newStrategy.driverDuel !== "") {

            // If a new GP bonus is selected and the target is required, we verify that the target is selected
            let isNewGPBonusTargetSelected = false;
            if(newStrategy.bonusGP !== savedStrategy.bonusGP) {
                const newGPBonus = bonusGP.find(bonus => bonus.uuid === newStrategy.bonusGP);
                if((newGPBonus?.targetType === "player" && newStrategy.bonusGPTarget !== "") || newGPBonus?.targetType === "self") {
                    isNewGPBonusTargetSelected = true;
                }
            }

            // Conditions required for submit button to be enabled : Or there is a change in the selected drivers or a new bonus is selected (and the target is selected if target is required) or we removed the bonus of the save strategy or we changed the target of the bonus from the saved strategy
            if(newStrategy.driverGP !== savedStrategy.driverGP || newStrategy.driverDuel !== savedStrategy.driverDuel || isNewGPBonusTargetSelected === true || newStrategy.bonusDuel !== savedStrategy.bonusDuel || (newStrategy.bonusGP === "" && newStrategy.bonusGP !== savedStrategy.bonusGP) || (newStrategy.bonusGPTarget !== "" && newStrategy.bonusGPTarget !== savedStrategy.bonusGPTarget)) {
                setSubmitButtonLabel(language.championship.race.cta.save);
                setIsStrategyComplete(true);
            } else {
                // If there are no changes at all, there's no save required
                setSubmitButtonLabel(language.championship.race.cta.no_save_required);
                setIsStrategyComplete(false);
            }
            // If a GP bonus is selected but the target is not selected, we can't save the strategy
            if(newStrategy.bonusGP !== "" && newStrategy.bonusGP !== savedStrategy.bonusGP && isNewGPBonusTargetSelected === false) {
                setSubmitButtonLabel(language.championship.race.cta.save_disabled_no_bonus_target);
                setIsStrategyComplete(false);
            }
        }
        // Define selected bonus item for GP if one is selected
        setSelectGPBonus(bonusGP.find(bonus => bonus.uuid === newStrategy.bonusGP) || undefined);
        // Define selected bonus item for Duel if one is selected
        setSelectedDuelBonus(bonusDuel.find(bonus => bonus.uuid === newStrategy.bonusDuel) || undefined);

        //console.log("strategy form - selected GP Bonus :", selectedGPBonus);
        //console.log("strategy form - selected Duel Bonus :", selectedDuelBonus);
    }

    const playerRemainingBudget = playerData.remainingBudget - (selectedGPBonus?.price || 0) - (selectedDuelBonus?.price || 0);

    async function submitStrategy (event: React.MouseEvent<HTMLButtonElement, MouseEvent>): Promise<void> {
        event.preventDefault();
        //console.log("submitted strategy", strategy);

        // First, we submit the driver for GP
        let driverUuid = {
            driverUuid: strategy.driverGP,
        }
        if(driverUuid.driverUuid !== savedStrategy.driverGP) {
            const resGPString: string = await selectDriver_action(driverUuid, championshipDataUuid, "gp");
            const resGP = JSON.parse(resGPString);

            if (resGP.status !== 1) {
                toast({
                    title: resGP.message,
                    variant: "destructive",
                });
                return;
            }
        }

        // Then if there's no error, we submit the driver for Duel
        driverUuid = {
            driverUuid: strategy.driverDuel,
        }
        if(driverUuid.driverUuid !== savedStrategy.driverDuel) {
            const resDuelString: string = await selectDriver_action(driverUuid, championshipDataUuid, "duel");
            const resDuel = JSON.parse(resDuelString);

            if (resDuel.status !== 1) {
                toast({
                    title: resDuel.message,
                    variant: "destructive",
                });
                return;
            }
        }

        // Then if there's no error, we remove the already existing bonus if there's none selected in the new strategy
        if(savedStrategy.bonusGP !== "" && strategy.bonusGP === "") {
            const bonusGPToUnselect: UnselectBonusDataType = {
                entityUuid: playerData.currentStrategy.uuid,
                type: 'strategy',
            }
            const resRemoveSavedBonusGPString: string = await unselectBonus_action(bonusGPToUnselect);
            const resRemoveSavedBonusGP = JSON.parse(resRemoveSavedBonusGPString);
            if (resRemoveSavedBonusGP.status !== 1) {
                toast({
                    title: resRemoveSavedBonusGP.message,
                    variant: "destructive",
                });
                return;
            }
        }

        // Then if there's no error, we submit the potential bonus for GP (strategy)
        if(strategy.bonusGP !== "") {
            if (strategy.bonusGP !== savedStrategy.bonusGP || (strategy.bonusGP === savedStrategy.bonusGP && strategy.bonusGPTarget !== "" && strategy.bonusGPTarget !== savedStrategy.bonusGPTarget)) {
                const bonusGPTarget = strategy.bonusGPTarget !== "" ? strategy.bonusGPTarget : null;
                const bonusGPSubmitted: SelectBonusDataType = {
                    entityUuid: playerData.currentStrategy.uuid,
                    type: 'strategy',
                    bonusUuid: strategy.bonusGP,
                    targetUuid: bonusGPTarget,
                }
                //console.log("bonusGPSubmitted : ", bonusGPSubmitted);
                const resBonusGPString: string = await selectBonus_action(bonusGPSubmitted);
                const resBonusGP = JSON.parse(resBonusGPString);

                if (resBonusGP.status !== 1) {
                    toast({
                        title: resBonusGP.message,
                        variant: "destructive",
                    });
                    return;
                }
            }
        }

        // Then if there's no error, we remove the already existing Duel bonus if there's none selected in the new strategy
        if(savedStrategy.bonusDuel !== "" && strategy.bonusDuel === "") {
            const bonusDuelToUnselect: UnselectBonusDataType = {
                entityUuid: playerData.currentDuel.uuid,
                type: 'duel',
            }
            const resRemoveSavedBonusDuelString: string = await unselectBonus_action(bonusDuelToUnselect);
            const resRemoveSavedBonusDuel = JSON.parse(resRemoveSavedBonusDuelString);
            if (resRemoveSavedBonusDuel.status !== 1) {
                toast({
                    title: resRemoveSavedBonusDuel.message,
                    variant: "destructive",
                });
                return;
            }
        }

        // Then if there's no error, we submit the potential bonus for Duel
        if(strategy.bonusDuel !== "" && strategy.bonusDuel !== savedStrategy.bonusDuel) {
            const bonusDuelSubmitted: SelectBonusDataType = {
                entityUuid: playerData.currentDuel.uuid,
                type: 'duel',
                bonusUuid: strategy.bonusDuel,
                targetUuid: playerData.currentDuel.opponent.uuid,
            }
            //console.log(bonusDuelSubmitted);
            const resBonusDuelString: string = await selectBonus_action(bonusDuelSubmitted);
            const resBonusDuel = JSON.parse(resBonusDuelString);

            if (resBonusDuel.status !== 1) {
                toast({
                    title: resBonusDuel.message,
                    variant: "destructive",
                });
                return;
            }
        }

        // If everything is ok, we display a success message
        toast({title: language.championship.race.toast.success});
        window.location.reload();
    }

    return (
        <>
            <Block containerClassName="block-animation mb-4" childClassName="pb-2">
                <div className="flex items-start justify-between p-4 pb-0">
                    <div>
                        <h2 className="h3">{language.championship.race.gp.title}</h2>
                        <p className="text-gray">
                        {raceStatus > 2 ? (
                            language.championship.race.gp.description_disabled
                        ) : (
                            language.championship.race.gp.description
                        )}
                        </p>
                    </div>
                    <PopinOpener type="help" popinId="popin-gp" />
                </div>
                <SelectDriverForm playerData={playerData} championshipDataUuid={championshipDataUuid} raceStatus={raceStatus} selectedStrategy={strategy} handleStrategy={handleStrategy} type="gp" />
                <SelectBonus uuid={strategy.bonusGP} bonus={selectedGPBonus} bonusTarget={strategy.bonusGPTarget} remainingBudget={playerData.remainingBudget} championshipPlayers={championshipPlayers} connectedPlayerUuid={playerData.uuid} raceStatus={raceStatus} selectedStrategy={strategy} handleStrategy={handleStrategy} type="gp" />
            </Block>

            {playerData.currentDuel && (
            <Block containerClassName={"block-animation " + (raceStatus === 2 && "mb-14") } childClassName="pb-2">
                <div className="flex items-start justify-between p-4 pb-0">
                    <div className="flex items-center">
                        <a href={OTHER_USER_PROFILE_PAGE(playerData.currentDuel.opponent.userUuid)}><CarInListing car={playerData.currentDuel.opponent.carImageUrl1} carColor={playerData.currentDuel.opponent.carColor} width={80} className="-ml-4 rounded-l-none rounded-r-full h-10 overflow-hidden" pictureClassName="translate-x-2" /></a>
                        <div className="rounded-full bg-primary w-6 h-6 flex items-center justify-center -ml-3 z-10 mr-3">
                            <Image src="/assets/icons/versus.svg"
                                alt=""
                                quality={100}
                                width={23}
                                height={8}
                            />
                        </div>
                        <h2 className="h3">{language.championship.race.duel.title}<br /><span className="text-primary">{playerData.currentDuel.opponent.name}</span></h2>
                    </div>
                    <PopinOpener type="help" popinId="popin-duel" />
                </div>
                <p className="text-gray px-4 pt-2">
                    {raceStatus > 2 ? (
                        language.championship.race.duel.description_disabled
                    ) : (
                        language.championship.race.duel.description
                    )}
                </p>
                <SelectDriverForm playerData={playerData} championshipDataUuid={championshipDataUuid} raceStatus={raceStatus} selectedStrategy={strategy} handleStrategy={handleStrategy} type="duel" />
                <SelectBonus uuid={strategy.bonusDuel} bonus={selectedDuelBonus} remainingBudget={playerData.remainingBudget} championshipPlayers={championshipPlayers} connectedPlayerUuid={playerData.uuid} raceStatus={raceStatus} selectedStrategy={strategy} handleStrategy={handleStrategy} type="duel" />
            </Block>
            )}

            {raceStatus === 2 &&
                <div className="fixed bottom-14 pb-4 pt-4 px-4 bg-gradient-to-t from-black from-85% w-full -ml-4 max-w-[740px]" id="button-submit-strategy">
                    <Button variant="default" onClick={submitStrategy} className={isStrategyComplete ? "" : "disabled:opacity-90"} disabled={isStrategyComplete ? false : true} >
                        {submitButtonlabel}
                    </Button>
                </div>
            }

            <Popin title="Utiliser un bonus pour le GP" id="popin-bonus-gp" className="-ml-4">
                <Carousel className="w-screen -ml-6 -mr-6" opts={{ align: "center", loop: true }}>
                    <CarouselContent className="-ml-4">
                        {bonusGP.map((bonus: BonusArray) => (
                            <CarouselItem key={bonus.uuid} className="basis-[320px] pl-4">
                                <StrategyCard bonus={bonus} remainingBudget={playerRemainingBudget} selectedStrategy={strategy} handleStrategy={handleStrategy} type="gp" />
                            </CarouselItem>
                        ))}
                    </CarouselContent>
                </Carousel>
            </Popin>

            <Popin title="Utiliser un bonus pour le duel" id="popin-bonus-duel" className="-ml-4">
                <Carousel className="w-screen -ml-6 -mr-6" opts={{ align: "center", loop: true }}>
                    <CarouselContent className="-ml-4">
                        {bonusDuel.map((bonus: BonusArray) => (
                            <CarouselItem key={bonus.uuid} className="basis-[320px] pl-4">
                                <StrategyCard bonus={bonus} remainingBudget={playerRemainingBudget} selectedStrategy={strategy} handleStrategy={handleStrategy} type="duel" />
                            </CarouselItem>
                        ))}
                    </CarouselContent>
                </Carousel>
            </Popin>
        </>
    )
}

export { StrategyForm };
