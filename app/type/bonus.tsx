import { ChampionshipActiveDuelOpponentArray } from "./championship";

export interface BonusArray {
    name: string,
    description: string,
    type: 'strategy' | 'duel',
    example: string,
    icon: string,
    uuid: string,
    price: number,
    cumulativeTimes: number | null,
    targetType: 'player' | 'self',
}

export interface BonusApplicationArray {
    bonus: BonusArray,
    player: ChampionshipActiveDuelOpponentArray,
    target: ChampionshipActiveDuelOpponentArray,
    uuid: string,
    balanceBefore: number,
    balanceAfter: number,
}
