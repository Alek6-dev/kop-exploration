# CLAUDE.md

## Démarrage rapide

```bash
# Lancer tout
make up

# App Next.js (dev local)
make app-dev          # http://localhost:3000

# API Symfony (Docker)
make api-up           # https://kop.local
```

**Interfaces à ouvrir :**
- App : https://localhost:3000/
- API / Admin : https://kop.local/admin/connexion
- Mailcatcher : http://localhost:1080

**Redémarrer PHP après un changement PHP :**
```bash
docker compose -f api/docker-compose.yml restart php
# Si 502 après redémarrage → recharger nginx :
docker exec kop-nginx-1 nginx -s reload
```

**Commandes utiles :**
```bash
make api-db           # Reset DB + fixtures
docker exec kop-php-1 php bin/console cache:clear
docker exec kop-mysql-1 mysql -u root -prootpassword symfony
```

**CGénérer les résultats :**
docker exec kop-php-1 php bin/console kop:f1:import-race 2026 5

---

## Projet — King of Paddock (KOP)

Fantasy league motorsport. Stack : **Symfony 6.4** (PHP 8.3, DDD+CQRS, API Platform) + **Next.js 14** (TypeScript, Tailwind, shadcn/ui).

```
api/src/
  Championship/  Race/  Driver/  Team/  Player/  Bid/  CreditWallet/
  SeasonGame/    ← domaine Mode Saison (indépendant)
  Shared/

app/app/(logged)/saison/   ← pages Mode Saison
app/actions/season-game/   ← server actions
```

---

## FEATURE EN COURS — Mode Saison ✦ branche `feat/mode-saison`

### Principe produit
Compétition permanente mondiale, 1 saison = 1 saison F1. Indépendant des Championnats.
- Budget composition : **500 M** · Équipe : **4 pilotes + 2 écuries**
- Usages max par élément : ~60% des courses restantes au moment de la validation
- Scoring GP : `((Perf P1 × 2) + Perf P2) × Multiplicateur Écurie`
- Monnaie Saison (`walletBalance`) : distincte des crédits Championnat
- Cartes Bonus : Parc Fermé (2¤), Assurance (10¤) — pas de bonus négatifs

### Modèle de données
`SeasonParticipation` → `SeasonRoster` → `SeasonRosterDriver` / `SeasonRosterTeam`
`SeasonParticipation` → `SeasonGPStrategy` (race, driver1×2, driver2, team, points, locked)
`SeasonParticipation` → `SeasonBonusUsage`

### Navigation
4 onglets : **Stratégie · Classement · Mon équipe · Palmarès**

### Cycle de vie
`Season.isActive = true/false` — géré en admin par Pierre.
`SeasonRace.limitStrategyDate` = deadline de la stratégie (début quali / début sprint).

### Ce qui reste à décider
- [ ] Montants des récompenses par palier percentile
- [ ] Gestion pilote remplacé en cours de saison

---

## Avancée sessions 1→4 (résumé)

- Hub restructuré (bouton Saison + nouveau layout 4 boutons)
- Toutes les pages saison créées et routées
- Composition d'équipe : formulaire, budget, localStorage, gestion 409
- Onglet **Stratégie** V1 complet : progressive disclosure (slots + accordéons), auto-save 800ms debounce, badge ×2, bordure dorée P1, `.custom-radio-neutral` pour P2/Écurie

---

## Avancée session 5 (2026-05-01)

### Bugs critiques corrigés

**1. Roster invisible au rechargement** (`DoctrineSeasonParticipationRepository`)
Doctrine ne charge pas automatiquement le côté inverse d'un `OneToOne(mappedBy)` dans une nouvelle requête HTTP. Fix : LEFT JOIN explicite sur `roster` dans `findByUserAndActiveSeason`.

**2. Stratégie jamais trouvée/sauvegardée** (`DoctrineSeasonGPStrategyRepository`)
Les UUID sont stockés en `BINARY(16)`. Passer une chaîne ASCII dans `setParameter` sans conversion → 0 résultats. Chaque save créait une nouvelle ligne (16 doublons en base). Fix : `UuidV4::fromString($raceUuid)->toBinary()` dans les deux méthodes du repo. Migration de nettoyage + contrainte `UNIQUE(participation_id, race_id)` ajoutée.

**3. GET stratégie → 500** (`SeasonGPStrategyResource`)
`uriVariables: ['raceUuid' => 'string']` invalide pour un item `Get` en API Platform 3 : IRI non générable. Fix : `new Link(fromClass: self::class, fromProperty: 'raceUuid')`. Aussi : `#[ApiProperty(identifier: false)]` sur `uuid` pour lever l'ambiguïté.

**4. 502 après restart PHP**
Nginx garde en cache l'ancienne IP du container PHP. Fix : `docker exec kop-nginx-1 nginx -s reload` après tout restart PHP.

### Onglet Mon équipe — implémenté

`app/app/(logged)/saison/mon-equipe/page.tsx` — server component.

**Zone Budget** (3 colonnes) : Budget dépensé · Solde actuel (jaune) · Récompenses
**Cards pilotes** (4) + **Cards écuries** (2) : layout horizontal, portrait 72px pleine hauteur, `gradient-avatar-driver/team`, barre d'usages skewed + compteur `X/total` (primary/gris), prix d'achat fixe (≠ prix marché) + icône `m.svg`.

Icônes : `/assets/icons/money/m.svg` (millions) · `/assets/icons/money/kop.svg` (crédits KOP championnats)

### À faire (après session 5)
- Onglet **Palmarès**
- **Cartes Bonus** (logique + UX)

---

## Avancée session 6 (2026-05-02)

### Onglet Classement Mode Saison — complet

2 vues via toggle **Saison | Par GP** :

**Vue Saison** : top 5 + séparateur `···` + ligne du joueur avec sa position réelle. Position affichée dans le header de la card.

**Vue Par GP** :
- Sélecteur dropdown (`SeasonGPSelector`) alimenté par `/season-game/scored-races` — seulement les GPs avec scores calculés, triés du plus récent au plus ancien
- Par défaut : GP le plus récent affiché au chargement
- Classement : top 3 + fenêtre de contexte (joueur −1 / joueur / joueur +1)
- Bouton **Détail** sur chaque ligne → `StrategyDetailPopin` (P1×2, P2, Écurie, Score, Bonus)
- Navigation par URL `?raceUuid=xxx`

Fichiers : `classement/_components/ClassementTabs.tsx`, `SeasonGPSelector.tsx`, `StrategyDetailPopin.tsx`, `page.tsx`, `app/actions/season-game/getSeasonScoredRaces-action.ts`

### Backend Mode Saison

- `SeasonGPStrategyResource` expose `userPseudo` + `userUuid`
- Endpoint `GET /season-game/scored-races`
- **Bug fix** : `ComputeSeasonGPScoresCommandHandler` utilisait `findGPRanking` (`points IS NOT NULL`) → ne scorait jamais rien. Remplacé par `findUncomputedStrategiesForRace` (`points IS NULL`). Handler idempotent.
- Calcul scores Saison déclenché automatiquement dans : admin CSV import, bouton "Générer performances", commande `kop:f1:import-race`

### Pipeline résultats — plus rien à faire manuellement

Après chaque GP, une seule commande suffit :
```bash
docker exec kop-php-1 php bin/console kop:f1:import-race 2026 <numero_gp>
```
Elle importe les résultats F1, calcule les perfs pilotes/écuries (Mode Championnat) et calcule les scores Mode Saison.

### Cron local (Docker)

Service `cron` ajouté dans `api/docker-compose.yml`. Lance toutes les minutes :
```
app:championship:assign-item → app:championship:assign-auto → app:championship:assign-races
app:championship:end-strategy
```
Miroir du cron prod. `make up` le démarre automatiquement.

### Optimisation enchères : déclenchement immédiat

`CreateBidProcessor` — quand le dernier joueur valide ses enchères, les 3 commandes (`assign-item` → `assign-auto` → `assign-races`) se déclenchent dans la même requête HTTP. Plus besoin d'attendre le cron.

### Fix championnat mode course

`StrategyForm.tsx` — null checks sur `currentStrategy` et `currentDuel` (crash quand aucune stratégie/duel n'existe encore pour le GP actif).

### À faire
- Onglet **Palmarès**
- **Cartes Bonus** (logique + UX)
- Onglet **Classement GP** : tester avec données réelles après Miami (2026-05-04)
