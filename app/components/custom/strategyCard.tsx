"use client"

import language from '@/messages/fr';
import Image from 'next/image';
import { Button } from '../ui/button';
import IconM from "@/public/assets/icons/money/m.svg";
import { SelectedStrategyArray } from '@/app/(logged)/championnat/[uuid]/(active)/course/components/StrategyForm';
import { BonusArray } from '@/type/bonus';

export interface StrategyCardProps {
    bonus: BonusArray,
    remainingBudget: number,
    selectedStrategy: SelectedStrategyArray,
    handleStrategy: any,
    type: 'gp' | 'duel',
    className?: string
}

const StrategyCard = ({ bonus, remainingBudget, selectedStrategy, handleStrategy, type, className }: StrategyCardProps) => {
    const disabledBonus:boolean = remainingBudget < bonus.price ? true : false;

    const submitBonus = async (
        uuid: string,
      ) => {
        if(type === "gp") {
            //console.log("bonus selected for GP :", uuid);
            handleStrategy({...selectedStrategy, bonusGP: uuid});
        }
        if(type === "duel") {
            //console.log("bonus selected for duel :", uuid);
            handleStrategy({...selectedStrategy, bonusDuel: uuid});
        }

        document.getElementById('popin-bonus-'+type)?.classList.remove("!translate-y-0");
        // Avoid scrolling and interaction of body when popin is open
        document.body.classList.toggle('touch-none');
        document.body.classList.toggle('overflow-hidden');
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

    return (
        <div className={"strategy-card " + className + (disabledBonus === true ? " disabled" : "")}>
            <div className="strategy-card-combinable">
                {combinableText}
            </div>
            <div className="strategy-card-icon">
                <Image src={`${process.env.NEXT_PUBLIC_API_URL + '/uploads/images/bonus/' +  bonus.icon}`} width={80} height={80} alt={bonus.name} quality={100} />
            </div>
            <div className="strategy-card-title">
                {bonus.name}
            </div>
            <div className="strategy-card-usage">
                {type}
            </div>
            <div className="strategy-card-text">
                <div className="strategy-card-description">
                    {bonus.description}
                </div>
                <div className="strategy-card-example-title">
                    <span className="mx-2">{language.bonus.card.example}</span>
                </div>
                <div className="strategy-card-example">
                    {bonus.example}
                </div>
            </div>
            <div className="strategy-card-button pl-6 flex items-center justify-between">
                <div className="flex items-center mr-4 shrink-0 font-bold text-white">
                    <IconM /><b className="ml-[7px]">{bonus.price}</b>
                </div>
                <Button className="rounded-none rounded-br-md px-2 leading-tight disabled:opacity-100 disabled:!gradient-primary-white disabled:!text-black" disabled={disabledBonus} onClick={() => submitBonus(bonus.uuid)}>
                    {disabledBonus === true ? language.bonus.card.not_enough_money : language.bonus.card.select}
                </Button>
            </div>
        </div>
    )
}

export { StrategyCard };
