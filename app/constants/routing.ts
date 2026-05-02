export const HOME_PAGE = "/";

// ERROR
export const NOTFOUND_PAGE = "/not-found";

// SECURITY
export const LOGIN_PAGE = "/connexion";
export const REGISTER_PAGE = "/inscription";
export const FORGOT_PASSWORD_PAGE = "/mot-de-passe-oublie";
export const RESET_PASSWORD_PAGE = "/reinitialisation-mot-de-passe";

// CHAMPIONSHIP
export const CHAMPIONSHIP_CREATE = "/championnat/creer";
export const CHAMPIONSHIP_JOIN = "/championnat/rejoindre";
export const CHAMPIONSHIP_LISTING_PAGE = "/championnat";
export const CHAMPIONSHIP_LISTING_FINISHED_PAGE = "/championnat/termines";
export const CHAMPIONSHIP_LOBBY_PAGE = (uuid:string):string => `/championnat/${uuid}`;
export const CHAMPIONSHIP_SILLYSEASON_PAGE = (uuid:string):string => `/championnat/${uuid}/sillyseason`;
export const CHAMPIONSHIP_SILLYSEASON_RESULTS_PAGE = (uuid:string):string => `/championnat/${uuid}/sillyseason/resultats`;
export const CHAMPIONSHIP_STRATEGY_PAGE = (uuid:string):string => `/championnat/${uuid}/course`;
export const CHAMPIONSHIP_RESULTS_PAGE = (uuid:string, raceUuid:string):string => `/championnat/${uuid}/resultats/${raceUuid}`;
export const CHAMPIONSHIP_DUEL_PAGE = (uuid:string, raceUuid:string):string => `/championnat/${uuid}/resultats/${raceUuid}/duels`;
export const CHAMPIONSHIP_RANKING_PAGE = (uuid:string):string => `/championnat/${uuid}/classement`;

// PROFILE
export const PROFILE_PAGE = "/profil";
export const OTHER_USER_PROFILE_PAGE = (uuid:string):string => `/profil/${uuid}`;
export const PROFILE_EDIT_PAGE = "/profil/editer";
export const PROFILE_DELETE_PAGE = "/profil/supprimer";
export const PROFILE_CONFIRMATION_DELETE_PAGE = "/confirmation-suppression";

// WALLET
export const WALLET_PAGE = "/portefeuille";
export const PAYMENT_CONFIRMATION_PAGE = "/statut-paiement";

// SHOP
export const SHOP_CARS_PAGE = "/boutique";
export const SHOP_HELMETS_PAGE = "/boutique/casques";

// PROMO CODE
export const PROMO_CODE_PAGE = "/code-promo";

// MY PADDOCK
export const MY_PADDOCK_PAGE = "/mon-paddock";

// QUIZ
export const QUIZ_PAGE = "/quiz";

// SEASON GAME
export const SEASON_GAME_PAGE = "/saison";
export const SEASON_GAME_COMPOSITION_PAGE = "/saison/composition";
export const SEASON_GAME_STRATEGY_PAGE = (raceUuid: string): string => `/saison/strategie/${raceUuid}`;
export const SEASON_GAME_RANKING_PAGE = "/saison/classement";
export const SEASON_GAME_MY_TEAM_PAGE = "/saison/mon-equipe";
export const SEASON_GAME_PALMARES_PAGE = "/saison/palmares";

// ABOUT
export const ABOUT_PAGE = "/a-propos";
export const CGU_PAGE = "/conditions-generales-utilisation";
export const CGV_PAGE = "/conditions-generales-vente";
export const LEGAL_MENTIONS_PAGE = "/mentions-legales";
export const PRIVACY_PAGE = "/politique-de-confidentialite";
