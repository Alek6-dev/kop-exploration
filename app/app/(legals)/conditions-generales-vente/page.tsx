import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { A } from "@/components/custom/link";
import { PRIVACY_PAGE } from "@/constants/routing";
import Link from "next/link";

export default function About() {
    return (
        <main>
            <Container>
                <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: language.cgv.title,
                    }}
                ></h1>
            </Container>

            <Container className="mt-6 mb-6">
                <Block childClassName="p-4 pt-4 block-cms">
                    <i>Version en date du 02/07/2024</i>
                    <p className="mt-4"><b>Préambule</b></p>
                    <p>Les présentes conditions générales de vente (« <b>CGV</b> ») définissent les conditions applicables lors de tout achat d’articles (les « <b>Articles</b> ») par un utilisateur personne physique (l’« Utilisateur ») via la ou les applications mobiles et sites internet de King of Paddock (le ou les « <b>Site(s)</b> » ou la ou les « <b>Application(s)</b> »).</p>
                    <p>L’Application et le Site sont édités par Fantasy King, société par actions simplifiée, SIREN n° 948 716 915, immatriculée auprès du RCS de Vienne, domiciliée à La Loco’Working, 4 impasse des frères lumière, 38300 BOURGOIN JALLIEU (« <b>Fantasy King</b> »).</p>
                    <p>Les présentes CGV constituent un contrat entre Fantasy King et l’Utilisateur.</p>
                    <p>Fantasy King pourra être contacté aux coordonnées indiquées à l’article 12 des présentes (« <b>Service Utilisateur</b> »).</p>
                    <p>L’Utilisateur ou Fantasy King sont désignés individuellement une « <b>Partie</b> », ou collectivement les « <b>Parties</b> ».</p>

                    <h2>Article 1 - Acceptation des CGV et des documents contractuels complémentaires</h2>
                    <p>Pour tout achat sur l’Application ou le Site, l’Utilisateur déclare et reconnaît expressément qu’il a lu attentivement l’ensemble des présentes CGV et les accepte pleinement et sans réserve.</p>
                    <p>En outre, l’Utilisateur déclare et reconnaît expressément qu’il a lu attentivement l’ensemble des conditions générales d’utilisation ainsi que la politique de confidentialité qui régissent sa navigation sur et son utilisation des Sites et Applications (les « <b>CGU</b> » et la « <b>Politique de Confidentialité</b> ») et les accepte pleinement et sans réserve.</p>

                    <h2>Article 2 - Les Articles</h2>
                    <p>Les Articles sont vendus par Fantasy King via les Applications ou Sites.</p>

                    <h2>Article 3 - Prix</h2>
                    <p>Les prix de vente des Articles affichés sur l’Application sont fermes et exprimés en euros toutes taxes comprises (TTC).</p>
                    <p>Les prix affichés sur l’Application peuvent faire l’objet de modifications à tout moment sans préavis. Toutefois, ces modifications n’affecteront pas les commandes déjà effectuées par un Utilisateur et confirmées par Fantasy King.</p>

                    <h2>Article 4 - Commande</h2>
                    <p>L’Utilisateur peut naviguer et jouer sur les Sites et Applications sans pour autant passer une commande.</p>
                    <p>Si l’Utilisateur souhaite procéder à l’achat d’Articles, il devra effectuer sa sélection et l’ajouter à son panier d’achat.</p>
                    <p>L’Utilisateur pourra sélectionner le ou les Articles souhaités et, pour chacun, le nombre voulu.</p>
                    <p>Afin de valider sa commande, l’Utilisateur devra cliquer sur le bouton « Accéder au paiement » et sera redirigé vers le prestataire externe Stripe.</p>
                    <p>L’Utilisateur pourra ensuite vérifier son panier, le contenu de sa commande, et le prix total à payer. </p>
                    <p>La commande sera définitivement validée lorsque le paiement aura été accepté, ce qui constituera la dernière étape de la commande.</p>
                    <p>Une fois la commande passée, l’Utilisateur recevra un message de confirmation de son paiement.</p>

                    <h2>Article 5 - Paiement</h2>
                    <p>Fantasy King utilise la solution de paiement sécurisée Stripe qui permet à l’Utilisateur de régler sa commande par carte bancaire lors d’un achat sur les Sites ou Applications. Les paiements par tout autre moyen de paiement ne sont pas acceptés.</p>
                    <p>Afin de finaliser sa commande, l’Utilisateur devra régler via le formulaire de commande au moyen de sa carte bancaire en renseignant son numéro de carte, la date d’expiration de celle-ci, le nom du porteur de la carte et le cryptogramme visuel (les trois ou quatre derniers numéros inscrits au dos de sa carte bancaire ou sur le devant de la carte selon la carte de paiement utilisée par l’Utilisateur). Les informations de paiement transitent uniquement par le prestataire de services de paiement Stripe et aucune information bancaire concernant l’Utilisateur ne transite par les Sites ou Applications ni n’est communiquée à Fantasy King.</p>
                    <p>Le paiement par carte bancaire est parfaitement sécurisé par Stripe. Fantasy King invite l’Utilisateur à se reporter aux règles de sécurité établies par Stripe et à ne jamais communiquer ses informations bancaires à un tiers.</p>
                    <p>Les paiements sont authentifiés et sécurisés grâce au système de sécurisation 3D Secure (appellé « Verified by Visa » pour une carte Visa et « MasterCard SecureCode » pour une MasterCard). Ce système, propre à chaque banque, permet à la banque de vérifier l’identité du porteur de la carte et de valider la transaction en demandant des informations personnelles.</p>
                    <p>Les informations relatives au paiement sont indiquées sur l’Application, étant précisé que si l’Utilisateur souhaite obtenir une aide complémentaire, il pourra contacter le service Utilisateur de Fantasy King aux coordonnées et horaires indiqués à l’article 15.</p>
                    <p>En cas de paiement refusé par la banque, la commande ne sera pas validée et l’Utilisateur sera prévenu de l’annulation de la transaction par l’envoi d’un courrier électronique.</p>


                    <h2>Article 6 - Droit de rétractation</h2>
                    <p>Conformément à l’article L.221-28, 13° du Code de la consommation, l’Utilisateur ne dispose pas d’un droit de rétractation à la suite d’un achat d’Articles.</p>
                    <p>L’Utilisateur est informé par les présentes CGV du fait qu’il ne dispose pas de droit de rétractation au moment de sa commande.</p>

                    <h2>Article 7 - Livraison</h2>
                    <p>A la suite de la réception du message de confirmation, les Articles seront immédiatement disponibles sur les Sites ou Application via le compte Utilisateur.</p>

                    <h2>Article 8 - Responsabilité – Force majeure</h2>
                    <p>Fantasy King ne saurait être tenu responsable de tout manquement ou retard d’exécution de l’une quelconque des stipulations des présentes CGV qui résulterait d’événements indépendants de sa volonté (« Cas de force majeure »).</p>
                    <p>En outre, tout événement pouvant être qualifié d’acte de force majeure (ci-après « Force Majeure ») pourra conduire à la suspension ou l’interruption des Sites ou Applications. La responsabilité de Fantasy King ne pourra être engagée lors d’un événement de Force Majeure. </p>
                    <p>La Force Majeure comprend tout fait ou omission dus à des circonstances irrésistibles, imprévisibles et indépendants de la volonté de Fantasy King, y compris, entre autres cas de Force Majeure, actions des autorités civiles ou militaires, incendies, intempéries de type gel, tempêtes, inondations, catastrophes naturelles, ou coupure générale de réseau électrique, coupure de l’accès routier ou interdiction de circulation.</p>
                    <p>Les obligations de Fantasy King, en vertu des présentes CGV seront automatiquement suspendues durant la période au cours de laquelle le Cas de force majeure se poursuit.</p>
                    <p>Fantasy King mettra tout en œuvre pour remédier aux inconvénients causés par le Cas de force majeure et pour trouver une solution lui permettant d’exécuter ses obligations contractuelles dans les meilleurs délais.</p>

                    <h2>Article 9 - Données personnelles</h2>
                    <p>Les CGV sont complétées par la Politique de Confidentialité accessible à l’adresse suivante : <A href={PRIVACY_PAGE}>https://app.kingofpaddock.com/politique-de-confidentialite</A></p>

                    <h2>Article 10 - Modification des CGV</h2>
                    <p>Fantasy King pourra modifier occasionnellement les présentes CGV notamment afin de refléter d’éventuelles évolutions réglementaires et législatives, les changements des conditions du marché, les changements des moyens de paiement, ainsi que pour toute autre raison que Fantasy King jugerait utile, à sa discrétion.</p>
                    <p>L’Utilisateur ne peut pas modifier ou réviser les CGV et aucune modification des CGV que l’Utilisateur tenterait d’effectuer n’engage Fantasy King.</p>
                    <p>L’Utilisateur sera lié par les CGV en vigueur au moment de sa commande sur l’Application.</p>

                    <h2>Article 11 - Droit applicable – Réclamations – Règlement des différends</h2>
                    <p>Les présentes CGV sont régies par le droit français.</p>
                    <p>En cas de différend concernant ces CGV ou une commande effectuée sur l’Application, l’Utilisateur est invité à soumettre sa réclamation au Service Utilisateur.</p>
                    <p>Si l’Utilisateur n’est pas satisfait de la réponse apportée à sa réclamation ou si aucune réponse ne lui a été adressée dans un délai de deux (2) mois, il peut saisir de sa réclamation un médiateur en vue de parvenir au règlement amiable grâce à la plateforme de la Comission Européenne pour la résolution des litiges accessible à l’adresse suivante : <Link href="http://ec.europa.eu/consumers/odr/" target="_blank" className="link">http://ec.europa.eu/consumers/odr/</Link></p>
                    <p>L’Utilisateur peut porter son litige devant les juridictions françaises compétentes dès la survenance du différend ou si aucun accord amiable n’est trouvé.</p>


                    <h2>Article 12 - Service Utilisateur</h2>
                    <p>L’Utilisateur peut contacter Fantasy King en envoyant un courrier électronique à <A href="mailto:contact@king-of-paddock.com">contact@king-of-paddock.com</A>.</p>
                    <p>Pour toute demande relative à une commande l’Utilisateur peut contacter Fantasy King par téléphone au 07 44 88 63 97 du lundi au vendredi de 9h30 à 13h et de 14h30 à 18h.</p>

                </Block>
            </Container>
        </main>
    );
}
