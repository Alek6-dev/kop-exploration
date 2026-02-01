import { BonusApplicationArray, BonusArray } from "./bonus"

export interface ChampionshipPlayerDriversArray {
    uuid?: string,
    firstName: string,
    lastName: string,
    name: string|null,
    color: string,
    team: {
        name: string|null,
    },
    minValue?: number,
    isReplacement: boolean,
    image?: string,
}

export interface ChampionshipActivePlayerDriversArray {
    uuid: string,
    firstName: string,
    lastName: string,
    name: string,
    color: string,
    minValue: number,
    isReplacement: boolean,
    team: {
        uuid: string,
        name: string,
        color: string,
        minValue: number,
        image: string,
    },
    replacementDateStart: string|null,
    replacementDateEnd: string|null,
    replacementBy: string|null,
    image: string,
}

export interface ChampionshipResultsDriverArray {
    uuid: string,
    firstName: string,
    lastName: string,
    name: string,
    color: string,
    minValue: number,
    isReplacement: boolean,
    team: {
        uuid: string,
        name: string,
        color: string,
        minValue: number,
        image: string,
    },
    image: string,
}

export interface ChampionshipPlayerTeamArray {
    uuid?: string,
    name: string|null,
    color: string,
    minValue?: number,
    image?: string,
}

export interface ChampionshipResultsTeamArray {
    uuid: string,
    name: string,
    color: string,
    minValue: number,
    image: string,
}

export interface ChampionshipPlayerBettingRoundWonArray {
    name: string,
    uuidItem: string,
    image: string,
    color: string,
    amount: number,
    round: number,
    assignBySystem: boolean
}

export interface championshipPlayerCosmeticArray {
    uuid: string,
    type: number,
    name: string,
    color: string,
    image1: string,
    image2: string
}

export interface ChampionshipPlayerUserArray {
    uuid: string,
    image: string,
    pseudo: string,
    email: string,
    carCosmetic: championshipPlayerCosmeticArray,
    helmetCosmetic: championshipPlayerCosmeticArray,
}

export interface ChampionshipPlayerArray {
    uuid: string,
    name: string,
    remainingBudget: number,
    selectedTeam: ChampionshipPlayerTeamArray,
    selectedDriver1: ChampionshipPlayerDriversArray,
    selectedDriver2: ChampionshipPlayerDriversArray,
    remainingUsageDriver1: number,
    remainingUsageDriver2: number,
    user: ChampionshipPlayerUserArray,
    bettingRounds: any,
    currentBettingRound: string,
    bettingRoundDriver1Won: ChampionshipPlayerBettingRoundWonArray,
    bettingRoundDriver2Won: ChampionshipPlayerBettingRoundWonArray,
    bettingRoundTeamWon: ChampionshipPlayerBettingRoundWonArray,
    point: number,
    score: number,
}

export interface ChampionshipActivePlayerStrategyArray {
    uuid: string,
    driver: ChampionshipActivePlayerDriversArray,
    bonusApplication: BonusApplicationArray,
}

export interface ChampionshipActiveDuelOpponentArray {
    uuid: string,
    userUuid: string,
    name: string,
    remainingBudget: number,
    remainingUsageDriver1: number,
    remainingUsageDriver2: number,
    remainingDuelUsageDriver1: number,
    remainingDuelUsageDriver2: number,
    point: number | null,
    position: number | null,
    score: number,
    carImageUrl1: string,
    carImageUrl2: string,
    carColor: string,
    helmetImageUrl1: string,
    helmetImageUrl2: string,
    helmetColor:string,
}

export interface ChampionshipActivePlayerDuelArray {
    uuid: string,
    driver: ChampionshipActivePlayerDriversArray,
    opponent: ChampionshipActiveDuelOpponentArray,
    bonusApplication: BonusApplicationArray,
}

export interface ChampionshipActivePlayerArray {
    uuid: string,
    name: string,
    remainingBudget: number,
    selectedTeam: ChampionshipPlayerTeamArray,
    selectedDriver1: ChampionshipActivePlayerDriversArray,
    selectedDriver2: ChampionshipActivePlayerDriversArray,
    remainingUsageDriver1: number,
    remainingUsageDriver2: number,
    remainingDuelUsageDriver1: number,
    remainingDuelUsageDriver2: number,
    maxRemainingUsageDriver: number,
    user: ChampionshipPlayerUserArray,
    bettingRounds: any,
    currentBettingRound: string,
    currentStrategy: ChampionshipActivePlayerStrategyArray,
    currentDuel: ChampionshipActivePlayerDuelArray,
    point: number | null,
    position: number | null,
    score: number,
}

export interface ChampionshipActiveRacesArray {
    uuid: string,
    name: string,
    flagUrl: string,
    date: string,
    limitStrategyDate: string,
    status: number,
}

export interface ChampionshipActiveDataArray {
    uuid: string,
    name: string,
    jokerEnabled: boolean,
    numberOfRaces: number,
    numberOfPlayers : number,
    invitationCode: string,
    status: number,
    players: ChampionshipActivePlayerArray[],
    season: string,
    createdBy: string,
    races: ChampionshipActiveRacesArray[],
    currentRound: number,
    currentRoundEndDate: string,
    countPlayersWithBidOnCurrentRound: number,
}

export interface ChampionshipDuelPlayerArray {
    name: string,
    uuid: string,
    car: string,
    carColor: string,
}

export interface ChampionshipResultsPlayerArray {
    uuid: string,
    userUuid: string,
    name: string,
    remainingBudget: number,
    remainingUsageDriver1: number,
    remainingUsageDriver2: number,
    remainingDuelUsageDriver1: number,
    remainingDuelUsageDriver2: number,
    point: number,
    position: number,
    score: number,
    carImageUrl1: string,
    carImageUrl2: string,
    carColor: string,
    helmetImageUrl1: string,
    helmetImageUrl2: string,
    helmetColor:string,
}
