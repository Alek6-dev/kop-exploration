import { toast } from "@/components/ui/use-toast";
import { redirect } from "next/dist/server/api-utils";

const language = {
    error: {
        page_not_found: {
            title: 'Cette page <span>n\'existe pas</span>',
            description: 'La page que tu cherches n\'existe pas ou a été déplacée. Tu peux retourner à l\'accueil en cliquant sur le bouton ci-dessous.',
            button: 'Retour à l\'accueil'
        },
        not_logged: 'Tu dois être connecté.'
    },
    form: {
        error: {
            required_field: 'Ce champs est requis',
            not_email: 'Ce champs doit etre un email',
            password_min_length: 'Ce champs doit faire au minimum 10 caractères dont au moins une majuscule, une minuscule et un caractère spécial.',
            non_matching_password: 'Les mots de passe doivent être similaires',
            required_file: 'Une image est requise',
            file_size: '3 Mo maximum',
            non_authorized_image_type: 'Votre image doit être au format JPG, JPEG, PNG ou WEBP'
        }
    },

    login: {
        page: {
            title: 'Connexion'
        },
        title: 'Se connecter',
        forgottenPassword: 'Mot de passe oublié ?',
        registerLink: 'Pas encore de compte ? Inscris-toi !',
        form: {
            field: {
                email: {
                    label: 'Adresse email',
                    placeholder: 'Entre ton email'
                },
                password: {
                    label: 'Mot de passe',
                    placeholder: 'Entre ton mot de passe'
                },
            },
            submit: {
                label: 'Se connecter'
            },
        }
    },

    registration: {
        page: {
            title: 'Inscription',
        },
        title: 'Créer un <span>compte</span>',
        form: {
            field: {
                email: {
                    label: 'Adresse email *',
                    placeholder: 'Entre ton email',
                },
                password: {
                    label: 'Mot de passe *',
                    placeholder: 'Entre ton mot de passe'
                },
                password_confirm: {
                    label: 'Confirmer le mot de passe *',
                    placeholder: 'Entre le même mot de poasse'
                },
                pseudo: {
                    label: 'Pseudonyme *',
                    placeholder: 'Entre ton pseudonyme'
                },
                image: {
                    pre_label: 'Photo de profil (facultatif)',
                    label: 'Uploader une photo',
                    description: 'JPG, JPEG PNG ou WebP. Maximum 3 Mo.',
                },
            },
            submit: {
                label: 'S\'inscrire'
            },
        },
        email: {
            subject: 'Validation de ton adresse email',
            preheader: '%name%, finalise ton inscription à King of Paddock',
            title: 'Finaliser mon inscription',
            content: ''
        },
        message: {
            email_unique: 'L\'email est déjà utilisé.',
            pseudo_unique: 'Le pseudo est déjà utilisé.',
            success_send_email: 'Compte enregistré avec succès, un mail de confirmation a été envoyé. Clique sur le lien du mail puis connecte-toi.',
            success_awaiting_moderation: 'Merci pour ton inscription. Ton compte est en attente de validation par notre équipe. Tu recevras un email de confirmation dès que ton compte sera activé.',
            unknown_error: 'Une erreur s\'est produite. Merci de rééessayer.',
            error_send_email: 'Le mail de validation n\'a pas pu être envoyé.',
        },
        validation: {
            title: 'Valider <span>mon inscription</span>',
            description: 'Ton adresse email est confirmée. Il ne te reste plus qu\'à appuyer sur le bouton ci-dessous pour valider ton inscription. Tu seras dès lors connecté et redirigé vers l\'écran d\'accueil.',
            submit: 'Valider mon inscription et jouer'
        }
    },

    forgot_password_request: {
        page: {
            title: 'Mot de passe oublié',
        },
        title: 'Mot de passe <span>oublié</span>',
        description: 'Tu as oublié ton mot de passe ? Pas de panique, ça arrive même aux meilleurs ! Indique l’adresse mail associée à ton compte et nous t’enverrons un email avec la marche à suivre pour réinitialiser ton mot de passe.',
        form: {
            field: {
                email: {
                    label: 'Votre adresse email',
                    placeholder: 'Entre ton email'
                },
            },
            submit: {
                label: 'Recevoir l\'email'
            },
            message: {
                success: (email) => `Un email de confirmation a été envoyé à l\'adresse ${email} `,
            },
        }
    },

    forgot_password_action: {
        page: {
            title: 'Réinitialisation du mot de passe',
        },
        title: 'Réinitialiser <span>mot de passe</span>',
        description: 'Tu y es presque ! Indique ton nouveau mot de passe ci-dessous et tu pourras accéder à ton compte. Pense à le noter quelque part cette fois :)',
        form: {
            field: {
                password: {
                    first_options: {
                        label: 'Mot de passe',
                        help: 'Ton mot de passe doit contenir au moins 10 caractères, dont 1 lettre, 1 chiffre et 1 caractère spécial.',
                        placeholder: 'Entre ton mot de passe'
                    },
                    second_options: {
                        label: 'Confirmation du mot de passe',
                        placeholder: 'Entre ton mot de passe'
                    },
                    error: {
                        invalid: {
                            message: 'Les mots de passe saisis ne correspondent pas.'
                        }
                    },
                }
            },
            submit: {
                label: 'Valider le mot de passe'
            },
        },
        message: {
            user_not_found: 'Le token de réinitialisation est invalide ou a expiré.',
            success: 'Ton mot de passe a été réinitialisé avec succès !'
        }
    },

    hub: {
        paddock: 'Personnaliser <br />mon paddock',
        paddock_menu: 'Mon paddock',
        championship: 'Championnats',
        quiz: 'Quiz',
        awards: 'KOP Awards',
        awards_menu: 'Awards',
        wallet: 'Portefeuille',
        store: 'Boutique',
        welcome: 'Bienvenue'
    },

    profile: {
        myprofile: {
            title: "Mon profil",
            logout: "Déconnexion"
        },
        other_user_profile: {
            title: '<div class="h1-subtitle">Profil du joueur</div> <span>{pseudo}</span>',
            stats: 'Statistiques',
        },
        edit: {
            title: '<div class="h1-subtitle">Mon Profil</div><span>Mes infos personnelles</span>',
            form: {
                submit: {
                    label: 'Sauvegarder mes informations'
                },
            },
            toast: {
                success: 'Ton profil a bien été mis à jour.',
                error: 'Une erreur est survenue lors de la mise à jour de ton profil.',
            },
        },
        links: {
            edit: {
                title: 'Informations personnelles',
                description: 'Edite ton email, mot de passe, avatar...'
            },
            about: {
                title: 'A propos de King of Paddock',
                description: 'CGU, Confidentialité, Mentions légales...'
            },
        },
        delete: {
            title: '<div class="h1-subtitle">Mon Profil</div><span>Supprimer mon compte</span>',
            description: '<b class="text-red text-medium">Attention, la suppression de ton compte est une opération irréversible.</b><br/><br/> En supprimant ton compte, tu ne pourras plus te reconnecter. <b>Tu perdras également tous tes jetons KOP et cosmétiques acquis.</b><br/><br/>Pour assurer le bon fonctionnement du jeu, tes participations et performances en championnat sont conservées. Mais ton email et ton pseudonyme d\'utilisateur seront anonymisés. <br/><br/>Si tu es sûr de vouloir supprimer ton  compte, clique sur le bouton ci-dessous.',
            button: 'Supprimer mon compte',
        },
        confirmation_delete: {
            title: 'Compte supprimé',
            description: 'Ton compte King of Paddock a bien été supprimé. Nous te remercions pour les moments passés sur notre jeu et gardons espoir de te revoir un jour.<br/><br/> Tu peux désormais fermer l\'application.',
        }
    },

    stats: {
        championships_won: 'Championnats remportés',
        duels_won: 'Duels gagnés',
        strategies_won: 'Courses gagnées',
        cosmetics_possessed: 'Cosmétiques possédés',
    },

    championship: {
        create: {
            page: {
                title: 'Créer un championnat',
            },
            title: 'Créer un <span>championnat</span>',
            form: {
                field: {
                    name: {
                        label: 'Nom du championnat',
                        placeholder: 'Entre un nom...',
                        error: "Le nom doit faire entre 3 et 30 caractères."
                    },
                    races: {
                        label: 'Nombre de Grand-Prix'
                    },
                    players: {
                        label: 'Nombre de joueurs'
                    },
                    joker: {
                        label: 'Jouer avec les jokers'
                    }
                },
                submit: {
                    label: 'Créer le championnat'
                },
            },
            toast:{
                success: 'Le championnat a bien été créé.'
            }
        },
        cancelled: {
            not_enough_races: {
                title: 'Ce championnat a été annulé.',
                description: 'En effet il ne reste plus assez de Grand Prix dans la saison de F1 pour mener ce championnat à termes. Cela peut arriver lorsque la durée entre la création du championnat et la fin des enchères a été particulièrement longue. Nous nous excusons pour la gène occasionée et espérons te voir la saison prochaine pour des compétitions endiablées.',
                link: 'Retourner aux championnats'
            }
        },

        invitation: {
            page: {
                title: 'En attente de joueurs'
            },
            title: '<div class="h1-subtitle">{championship_name}</div> <span>en attente de joueurs</span>',
            section: {
                share_code: {
                    title: {
                        label: 'Utiliser un code d\'invitation',
                    },
                    description: {
                        label: 'Invite tes amis à rejoindre « {championship_name} » en leur transmettant le code ci-dessous. <b>Gagne 10 jetons KOP</b> {icon} si un joueur s\'inscrit sur King of Paddock pour rejoindre ton championnat.'
                    },
                    invitation_code: {
                        label: 'Code d\'invitation'
                    },
                    button: {
                        label: 'Partager le code',
                        data_share: {
                            title: {
                                label: 'Rejoins une fantasy league King of Paddock !'
                            },
                            description: {
                                label: 'Tu es invité par {creator_pseudo} à rejoindre le championnat {championship_name} sur l\'application King of Paddock. Connecte-toi et utilise le code {invitation_code} pour jouer avec tes amis !'
                            },
                            url: {
                                label: 'https://app.kingofpaddock.com'
                            },
                        },
                    },
                },
                waiting: 'Ce championnat débutera lorsque le nombre de joueurs sera atteint ou lorsque que son créateur l\'aura décidé.',
                players: {
                    title: {
                        label: 'Joueurs inscrits <span class="text-primary">{count} sur {max}</span>'
                    },
                },
                start: {
                    title: {
                        label: 'Lancer le championnat manuellement'
                    },
                    description: {
                        label: 'Si au moins 4 joueurs sont inscrits et que le nombre de joueurs est pair, tu peux lancer le championnat manuellement. Sinon, attends simplement que le nombre de joueurs souhaité soit atteint et le championnat débutera automatiquement.'
                    },
                    button: {
                        label: 'Lancer le championnat'
                    },
                },
                cancel: {
                    button: {
                        label: 'Annuler le championnat'
                    },
                },
            },
            toast: {
                cancel: {
                    success: 'Le championnat a bien été annulé.',
                    error: 'Une erreur est survenue lors de l\'annulation du championnat.'
                },
                start: {
                    success: 'Le championnat est lancé. Place à la Silly Season !'
                }
            }
        },

        join: {
            page: {
                title: 'Rejoindre un championnat',
            },
            title: 'Rejoindre un <span>championnat</span>',
            description: 'Pour rejoindre un championnat existant, tu dois être en possession d\'un code d\'invitation envoyé par le créateur de ce championnat.',
            warning: 'Le nom de ton écurie est propre à ce championnat et n\'est plus modifiable après cette étape.',
            form: {
                field: {
                    team: {
                        label: 'Nom de ton écurie personnelle',
                        placeholder: 'Entre un nom...',
                        error: "Le nom doit faire entre 3 et 25 caractères."
                    },
                    code: {
                        label: 'Code d\'invitation du championnat',
                        placeholder: 'Entre un code...'
                    },
                },
                submit: {
                    label: 'Rejoindre le championnat'
                },
            },
            toast:{
                success: 'Félicitations. Tu as rejoint ce championnat.'
            }
        },

        list:{
            page:{
                title:{
                    label: 'Championnats'
                }
            },
            title: 'Championnats',
            tab:{
                in_progress:{
                    label: 'En cours'
                },
                over:{
                    label: 'Terminés'
                },
            },
            button:{
                create:{
                    label: 'Créer'
                },
                join:{
                    label: 'Rejoindre'
                },
            },
            gp: 'GP',
            ranking: 'Classement',
            status: {
                awaiting_players: 'En attente de joueurs',
                sillyseason: 'Silly Season en cours',
                strategy: 'Stratégie en cours',
                awaiting_results: 'En attente des résultats de la course',
            },
            cancelled: 'Championnat annulé',
            cancellation_reason: {
                manual: 'par l\'organisateur',
                missing_gp: 'faute de GP restants pour le mener à termes'
            },
        },

        sillyseason: {
            page:{
                title:{
                    label: 'Silly Season'
                }
            },
            title: '<div class="h1-subtitle">{championship_name}</div> <span>Silly Season</span>',
            myteam:{
                title: 'Mon équipe',
                empty: 'Tu n\'as pas encore de pilotes ou d\'écurie.',
                howitworks: 'Comment ça marche ?',
                budget: 'Budget initial :',
                driver: 'Pilote',
                team: 'Ecurie',
                undefined: 'A définir',
                complete: '<b class="text-primary">Bravo, tu as terminé ta composition d’équipe. Patiente jusqu’à la fin des tours d’enchères des autres joueurs. Tu peux consulter les résultats à tout moment en cliquant sur le bouton Résultats ci-dessus.</b>',
            },
            bettingRound:{
                title: 'Enchères Tour n°',
                time_left: 'Fin dans ',
                time_end: 'le ',
                state: 'joueurs ont finalisés leurs enchères.',
                state_singular: 'joueur a finalisé ses enchères.',
                state_empty: 'Aucun joueur n\'a encore finalisé ses enchères.',
                remaining_budget: 'Budget restant tour :',
                your_bid: "Ton enchère :",
                driver: "Pilote :",
                team: "Ecurie :",
                results_in_progress: 'Patiente un court instant, le calcul des résultats des enchères de ce tour est en cours.',
            },
            tab:{
                drivers:{
                    label: 'Pilotes',
                    description: 'Enchéris sur deux pilotes de ton choix.',
                    description_singular: 'Enchéris sur le pilote de ton choix.',
                    description_submitted: 'Tes enchères sont placées. Patiente jusqu\'à la fin du tour pour connaître les attributions.',
                },
                teams:{
                    label: 'Ecuries',
                    description: 'Enchéris sur l\'écurie de ton choix.',
                    description_submitted: 'Tes enchères sont placées. Patiente jusqu\'à la fin du tour pour connaître les attributions.',
                },
            },
            button:{
                validate:{
                    negative_budget: 'Réduis ton budget',
                    missing_items: 'Sélectionne {countDriver} pilote{s} & {countTeam} écurie',
                    missing_item_team: 'Sélectionne {countTeam} écurie',
                    missing_item_driver: 'Sélectionne {countDriver} pilote{s}',
                    submit: 'Valider ma sélection'
                },
            },
            toast: {
                success: 'Tes enchères ont bien été enregistrées.',
            },
            popin: {
                title: "Aide Silly Season",
                p1: "Pour créer ton équipe, tu dois enchérir sur deux pilotes et une écurie.",
                p2: "Les prix indiqués représentent la valeur minimum des éléments. Tu peux faire le choix de miser plus que le prix de base, mais en respectant un budget de 300 millions pour l'achat de tes trois éléments.",
                p3: "En cas d’égalité entre deux ou plusieurs joueurs pour l’achat d’un élément, ce dernier est attribué au joueur ayant validé ses enchères en premier.",
                p4: "À noter : dans le cas où un ou plusieurs joueurs n’auraient plus suffisamment de budget pour compléter son équipe, ou si un joueur n’a pas effectué de sélection lors de deux tours d’enchères successifs, une attribution automatique est effectuée selon la règle suivante : ",
                p5: "Les éléments les moins chers sont affectés selon les plus petits budgets restants.",
                p6: "Ce cas de figure peut entraîner un budget négatif au début du championnat. Il pénalise le joueur concerné et l’empêche de pouvoir jouer des Bonus.",
            },
        },

        sillyseason_results: {
            page:{
                title:{
                    label: 'Résultats de la Silly Season'
                }
            },
            title: '<div class="h1-subtitle">{championship_name}</div> <span>Silly Season</span>',
            results: 'Résultats des enchères',
            description: 'Découvre les résultats des enchères et le budget dépensé par chaque joueur, tour par tour.',
            round: 'Tour :'
        },

        race: {
            page:{
                title:{
                    label: 'Course'
                }
            },
            title: '<div class="h1-subtitle">{championship_name}</div> <span>Course n°{race_number}</span>',
            titleOver: '<div class="h1-subtitle">{championship_name}</div> <span>Terminé</span>',
            tabs: {
                strategy: 'Stratégie',
                results: 'Résultats',
                ranking: 'Général',
            },
            gp: {
                title: 'GP KOP',
                description: 'Sélectionne ton pilote n°1.',
                description_disabled: 'Ton pilote n°1 est sélectionné. Tu ne peux plus le modifier.',
                popin: {
                    title: "Stratégie GP KOP",
                    title1: "Choisis ton pilote n°1 pour le GP KOP.",
                    p1: "Ce choix est décisif pour ton score du week-end, car les points du pilote sélectionné sont doublés sur le GP KOP. Son nombre d’utilisation est limité en fonction du nombre de courses sur lesquelles se déroule ta compétition.",
                    title2: "Choisis ton Bonus pour le GP KOP.",
                    p2: "Si ton budget te le permet, tu peux décider de jouer un bonus qui influencera le résultat du GP KOP.",
                    title3: "Gains de budget",
                    p3: "Ta position en GP KOP détermine une somme en {M} qui est ajoutée à ton budget (10 {M} pour le 1er ; 9 {M} pour le 2e ; 8 {M} pour le 3e ; etc)",
                },
            },
            duel: {
                title: 'Duel rivalité',
                description: 'Sélectionne ton pilote titulaire.',
                description_disabled: 'Ton pilote titulaire est sélectionné. Tu ne peux plus le modifier.',
                popin: {
                    title: "Stratégie Duel Rivalité",
                    title1: "Choisis ton pilote titulaire pour le Duel Rivalité.",
                    p1: "Ce choix est décisif pour ton duel du week-end, car le pilote sélectionné affronte en duel le pilote choisi par ton adversaire. Leurs performances pilotes seront comparées l’une à l’autre pour déterminer le vainqueur. Le nombre d’utilisation est limité en fonction du nombre de courses sur lesquelles se déroule ta compétition.",
                    title2: "Choisis ton Bonus pour le Duel Rivalité.",
                    p2: "Si ton budget te le permet, tu peux décider de jouer un bonus qui influencera le résultat de ton Duel.",
                },
            },
            bonus: {
                playedBonus: 'Bonus joué :',
                cta: 'Retirer',
                cta_use: 'Utiliser un bonus',
                popin_usage_title: 'Usage des bonus',
                combinable: 'Usage cumulable',
                combinable_shorttext: 'Cumulable',
                combinable_explanation: "Plusieurs joueurs peuvent jouer un bonus identique contre un même joueur lors du GP KOP. Les effets de chacun de ces bonus s'additionnent alors lors du calcul des scores. Si une limite de cumul existe, les joueurs dont le bonus n’a pas été appliqué récupèrent leurs millions dépensés.",
                not_combinable: 'Usage non cumulable',
                not_combinable_shorttext: 'Non cumulable',
                not_combinable_explanation: "Si plusieurs joueurs jouent un bonus identique contre un même joueur lors du GP KOP, seul le bonus du joueur ayant validé sa stratégie en premier sera appliqué lors du calcul des scores. Les autres joueurs récupèrent les millions dépensés pour ce bonus, car celui-ci ne sera pas appliqué.",
            },
            usage: "usage",
            remaining: "restant",
            race: "Course :",
            budget: 'Budget :',
            strategy_end: 'Il te reste',
            strategy_end2: 'pour finaliser ta statégie (fin le',
            strategy_time_passed: 'Le délai pour définir ta stratégie est écoulé. Les résultats seront disponibles quelques heures après la course.',
            cta: {
                save: 'Enregistrer ma stratégie',
                save_disabled: 'Sélectionne un pilote GP et Duel',
                save_disabled_no_bonus_target: 'Sélectionne la cible du bonus GP',
                no_save_required: 'Aucun changement à enregistrer'
            },
            toast: {
                success: 'Ta stratégie a bien été enregistrée.',
            }
        },

        results: {
            no_results: 'La première course de ta compétition n\'a pas encore eu lieu. Il n\'y a donc pas de résultats consutables pour le moment. Tu pourras consulter les résultats des courses sur cet écran une fois celles-ci disputées.',
            race: 'Course',
            scorecolon: 'Score :',
            pq: 'PQ',
            pc: 'PC',
            pcs: 'PCS',
            gpo: 'GPO',
            pp: 'PP',
            score: 'Score',
            multiplicator: "Multiplicateur :",
            pts: 'PTS',
            pt: 'PT',
            tabs: {
                results: 'Résultats GP KOP',
                duel: 'Duels rivalités',
            },
            cta_bonus: 'Voir les bonus / malus joués',
            played_bonus: {
                title: 'Bonus / malus joués sur le joueur',
                title_duel: 'Bonus / malus joués dans ce duel',
                playedby: 'Joué par ',
                reimbursement: 'Bonus non utilisé car non cumulable et déjà joué par un autre joueur. Les jetons KOP ont été remboursés.'
            }
        },

        ranking: {
            title: 'Classement général',
            description: 'Découvre qui règne sur le championnat. Tu peux aussi accéder à la ',
            link: 'composition des équipes',
        },
    },

    wallet: {
        page: {
            title: 'Portefeuille de Jetons KOP'
        },
        title: '<div class="h1-subtitle">Portefeuille</div> <span>jetons KOP</span>',
        available: 'Jetons disponibles',
        tokens: 'jetons KOP',
        token: 'jeton KOP',
        utility: {
            title: 'À quoi servent les jetons KOP ?',
            description: 'Les jetons KOP te permettent d’acquérir des cosmétiques (monoplaces et casques) pour personnaliser ton profil. Pour en obtenir, deux possibilités s’offrent à toi :',
            list1: "Le parrainage : invite un joueur encore non-inscrit sur KOP à rejoindre ton championnat (10 jetons KOP par parrainage)",
            list2: "L’achat d’un pack de jetons KOP",
        },
        buy: {
            title: 'Acheter des jetons KOP',
        },
        form: {
            field: {
                pack: {
                    label: 'Sélectionne un pack de jetons (économise grâce à nos tarifs dégressifs)',
                    error: "Sélectionne un pack."
                },
                cgv: {
                    label: 'J\'accepte les',
                    label2: 'Conditions Générales de Vente'
                },
                retractation: ' Je reconnais que je ne dispose pas d’un droit de rétractation à la suite de ma commande ',
            },
            submit: {
                label: 'Accéder au paiement'
            },
        },
        toast: {
            buy_success: 'Tes jetons KOP ont bien été ajoutés à ton portefeuille. Merci pour ton achat.',
            redirect_payment_page: 'Redirection vers la page de paiement',
        },
        payment_confirmed: {
            title: 'Paiement confirmé',
            description: 'Ton paiement a bien été confirmé. Nous ajoutons tes nouveaux jetons KOP à ton portefeuille (cette opération peut prendre quelques secondes).',
            thanks: 'Merci pour ton achat et bon jeu !',
            cta: {
                back_to_wallet: 'Retourner à mon portefeuille',
                back_to_shop: 'Acheter des cosmétiques',
            }
        },
        payment_cancelled: {
            title: 'Paiement annulé',
            description: 'Le processus de paiement a été annulé. Aucun jeton KOP n\'a été crédité sur ton portefeuille et aucun montant n\'a été débité de ton compte bancaire.',
            retry: 'Tu peux retourner sur ton portefeuille pour réssayer.',
        }
    },

    shop: {
        page: {
            title: 'Boutique'
        },
        title: 'Boutique',
        title_car: '<div class="h1-subtitle">Sélectionne ta</div> <span>monoplace</span>',
        title_helmet: '<div class="h1-subtitle">Sélectionne ton</div> <span>casque</span>',
        not_enough_credits: 'Jetons KOP insuffisants',
        not_enough_credits_description: 'Tu n\’as pas assez de Jetons KOP pour acquérir ce cosmétique',
        buy_credits: 'Acheter des jetons KOP',
        tabs: {
            cars: 'Monoplaces',
            helmets: 'Casques',
            suits: 'Combinaisons',
        },
        buttons: {
            buy: 'Acheter',
            confirm_buy: 'Confirmer l\'achat',
            equip: 'Equiper',
            equipped: 'Equipé',
        },
        toast: {
            buy_success: 'Le cosmétique a bien été acheté.',
            equip_success: 'Le cosmétique a bien été équipé.',
        }
    },

    promocode: {
        page: {
            title: 'Utiliser un code promo'
        },
        title: 'Utiliser un <span>code promo</span>',
        description: 'Vous avez un code promo ? Entrez-le ci-dessous pour obtenir vos avantages.',
        congrats: 'Félicitations.',
        earnings: 'Découvrez ci-dessous les éléments que vous avez gratuitement obtenus grâce à votre code promo.',
        form: {
            field: {
                label: 'Code promo',
                placeholder: 'Entrez votre code promo',
            },
            submit: 'Valider le code',
        },
        success: 'Le code promo a bien été appliqué.'
    },

    mypaddock: {
        page: {
            title: 'Mon paddock'
        },
        title: 'Mon paddock',
        car: 'Monoplace',
        helmet: 'Casque',
        buttons: {
            change: 'Changer',
        },
    },

    about: {
        page: {
            title: 'A propos de King of Paddock'
        },
        title: 'A propos',
        cgu: {
            'title': 'Conditions générales d\'utilisation',
        },
        cgv: {
            'title': 'Conditions générales de vente',
        },
        privacy: {
            'title': 'Politique de confidentialité',
        },
        legal: {
            'title': 'Mentions légales',
        },
    },

    cgu: {
        page: {
            title: 'Conditions générales d\'utilisation'
        },
        title: 'Conditions générales d\'utilisation - King of Paddock',
    },

    cgv: {
        page: {
            title: 'Conditions générales de vente'
        },
        title: 'Conditions générales de vente - King of Paddock',
    },

    privacy: {
        page: {
            title: 'Politique de confidentialité'
        },
        title: 'Politique de confidentialité - Fantasy King',
    },

    legal: {
        page: {
            title: 'Mentions légales'
        },
        title: 'Mentions légales',
    },

    bonus : {
        card: {
            example: 'Exemple',
            select: 'Jouer ce bonus',
            not_enough_money: 'Pas assez de budget',
            combinable: 'Cumulable',
            not_combinable: 'Non cumulable',
        }
    },

    quiz: {
        page:{
            title:{
                label: 'Quiz'
            }
        },
        title: '<div class="h1-subtitle">Prochainement disponible</div> <span>Quiz</span>',
        description: "Envie de tester tes connaissances sur la F1 ? Rendez-vous prochainement pour jouer à notre Quiz KOP et tenter de remporter des récompenses !",
        crowdfunding1: "King Of Paddock est un jeu de Fantasy F1 édité par un studio indépendant qui se développe essentiellement grâce à l’engagement de sa communauté.",
        crowdfunding2: "Plein de nouvelles fonctionnalités, comme le quiz KOP, sont à l’usine et sont prêtes à être développées par nos meilleurs ingénieurs, pour continuer d’améliorer le jeu que nous voulons construire avec vous.",
        crowdfunding3: "Pour nous aider à financer ces futurs projets et pour que nos joueurs puissent prendre part au développement de King Of Paddock, nous avons ouvert une cagnotte en ligne à laquelle tu peux participer en cliquant sur ce lien :",
        cta: 'Participer au financement'
    }
}

export default language;
