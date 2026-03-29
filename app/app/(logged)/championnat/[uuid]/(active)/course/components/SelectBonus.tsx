"use client"

import { PopinOpener } from "@/components/custom/popinOpener";
import { StrategyCard } from "@/components/custom/strategyCard";
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import IconM from "@/public/assets/icons/money/m.svg";
import { SelectedStrategyArray } from "./StrategyForm";
import { BonusArray } from "@/type/bonus";
import { ChampionshipActivePlayerArray } from "@/type/championship";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Separator } from "@/components/custom/separator";

export interface SelectBonusProps {
    uuid: string,
    selectedStrategy: SelectedStrategyArray,
    remainingBudget: number,
    bonus: BonusArray | undefined,
    bonusTarget?: string | undefined,
    championshipPlayers: Array<ChampionshipActivePlayerArray>,
    connectedPlayerUuid: string,
    type: 'gp' | 'duel',
    raceStatus: number,
    handleStrategy: any,
    className?: string,
}

const SelectBonus = ({ uuid, selectedStrategy, remainingBudget, bonus, bonusTarget, championshipPlayers, connectedPlayerUuid, type, raceStatus, handleStrategy, className }: SelectBonusProps) => {

    const removeBonus = async () => {
        if(type === "gp") { handleStrategy({...selectedStrategy, bonusGP: "", bonusGPTarget: ""}) }
        if(type === "duel") { handleStrategy({...selectedStrategy, bonusDuel: ""}) }
    }

    const submitBonusTarget = async (value: string) => {
        if(type === "gp") { handleStrategy({...selectedStrategy, bonusGPTarget: value}) }
    }

    let combinable: boolean = false;
    let combinableText = language.championship.race.bonus.not_combinable_shorttext;
    if(bonus !== undefined) {
        if(bonus.cumulativeTimes === null || bonus.cumulativeTimes > 1) {
            combinable = true;
            combinableText = language.championship.race.bonus.combinable_shorttext;
            if(bonus.cumulativeTimes !== null && bonus.cumulativeTimes > 1) {
                combinableText = language.championship.race.bonus.combinable_shorttext + " " + bonus.cumulativeTimes + " fois";
            }
        }
    }

    return(
        <div className="px-4 mt-4">
            {bonus !== undefined ?
                <div className="bg-black rounded-lg p-4 pb-0 mb-2 overflow-hidden relative block">
                    <div className="w-full flex justify-between items-baseline mb-2">
                        <span><b>{language.championship.race.bonus.playedBonus} <span className="text-primary">{bonus.name}</span></b></span>
                        <div className="flex items-center ml-4 shrink-0 font-bold text-white">
                            <b className="mr-[7px]">{bonus.price}</b><IconM />
                        </div>
                    </div>
                    <div className="w-full relative overflow-hidden">
                        <div className="w-[calc(100%-130px)] pb-4">
                            <PopinOpener type="help-text" popinId="popin-bonus-usage" className="text-tiny mb-[2px]" title={combinableText} />
                            <p className="text-tiny text-gray mb-2">{bonus.description}</p>
                            {raceStatus === 2 &&
                                <Button variant="light" size="sm" className="flex-initial w-auto button-bonus" onClick={() => removeBonus()}>{language.championship.race.bonus.cta}
                            </Button>}
                        </div>
                        <div className="absolute right-0 top-0 scale-[35%] origin-top-right rotate-6 pt-6">
                            <StrategyCard bonus={bonus} remainingBudget={remainingBudget} selectedStrategy={selectedStrategy} handleStrategy={handleStrategy} className="pointer-events-none" type={type} />
                        </div>
                    </div>
                    {(bonus.targetType === "player" && bonus.type === "strategy") &&
                        <>
                            <Separator className="separator-full" />
                            <div className="w-full pt-3 pb-4 select-player">
                                <b className="block mb-1">Joueur ciblé par le bonus :</b>
                                <Select defaultValue={bonusTarget !== undefined ? bonusTarget : ""} onValueChange={(value: string) => {submitBonusTarget(value)}} disabled={raceStatus !== 2}>
                                    <SelectTrigger className="w-full border-white/10 appearance-none hover:border-white focus:border-white focus-within:border-white">
                                        <SelectValue placeholder="Sélectionner le joueur ciblé" />
                                    </SelectTrigger>
                                    <SelectContent className="bg-black shadow-[0_50px_100px_0_rgba(0,0,0,0.8)] border-white-6 p-0 z-10">
                                        {championshipPlayers.map((player: ChampionshipActivePlayerArray) => (
                                            (player.uuid !== connectedPlayerUuid) &&
                                                <SelectItem value={player.uuid} key={player.uuid} className="h-8 pr-2 text-base border-white/10 border-b mx-0 last:border-none w-full rounded-none">{player.name}</SelectItem>
                                            )
                                        )}
                                    </SelectContent>
                                </Select>
                            </div>
                        </>
                    }

                </div>
            :
                (raceStatus === 2) && <PopinOpener type="button" popinId={type === "gp" ? "popin-bonus-gp" : "popin-bonus-duel"} className="button-bonus mb-2" title={language.championship.race.bonus.cta_use} />
            }
        </div>
    )
}

export { SelectBonus };
