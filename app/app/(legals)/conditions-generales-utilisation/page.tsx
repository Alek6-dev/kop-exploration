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
                        __html: language.cgu.title,
                    }}
                ></h1>
            </Container>

            <Container className="mt-6 mb-6">
                <Block childClassName="p-4 pt-4 block-cms">
                    <i>Version en date du 02/07/2024</i>
                    <p className="mt-4"><b>Préambule</b></p>
                    <p>Les présentes conditions générales d’utilisation (« <b>CGU</b> ») régissent les conditions applicables lors de l’utilisation de la ou les applications mobiles et sites internet de King of Paddock (le ou les « <b>Site(s)</b> » ou la ou les « <b>Application(s)</b> »).</p>
                    <p>L’Application et le Site sont édités par Fantasy King, société par actions simplifiée, SIREN n° 948 716 915, immatriculée auprès du RCS de Vienne, domiciliée à La Loco’Working, 4 impasse des frères lumière, 38300 BOURGOIN JALLIEU (« <b>Fantasy King</b> »).</p>
                    <p>Fantasy King pourra être contacté aux coordonnées indiquées à l’article 14 des présentes (le « <b>Service Utilisateur</b> »).</p>
                    <p>L’Utilisateur ou Fantasy King sont désignés individuellement une « <b>Partie</b> », ou collectivement les « <b>Parties</b> ».</p>

                    <h2>Article 1 - Acceptation des CGU</h2>
                    <p>En utilisant le Site ou l’Application et en y réalisant des actions, l’Utilisateur déclare et reconnaît expressément qu’il a lu attentivement l’ensemble des présentes CGU, les accepte pleinement et sans réserve et s’engage à les respecter.</p>

                    <h2>Article 2 - Accès aux Sites ou aux Applications</h2>
                    <p>Afin d’accéder à l’Application, l’Utilisateur doit disposer d’un accès au réseau Internet ainsi qu’à un navigateur internet. L’Utilisateur devra disposer des connaissances nécessaires à l’utilisation d’Internet et des applications mobiles.</p>
                    <p>Les Applications sont accessibles via les plateformes d’applications mobiles sur lesquels Fantasy King aura choisi de les mettre à disposition. Les Sites pour leur part sont accessibles via leurs URL.</p>
                    <p>Les Applications et Sites pourront être modifiés afin d’être mise à jour, d’y ajouter de nouvelles fonctionnalités ou d’améliorer l’expérience utilisateur.</p>
                    <p>L’accès aux Applications et aux Sites se fait aux risques et périls de chaque Utilisateur.</p>
                    <p>Fantasy King interdit formellement l’accès non autorisé aux services des Applications ou des Sites ou d’y accéder ou de s’y maintenir par tout autre moyen que les interfaces fournis par Fantasy King.</p>

                    <h2>Article 3 - Création d’un profil Utilisateur</h2>
                    <p>L’acceptation des CGU est indispensable à la création d’un profil Utilisateur.</p>
                    <p>Chaque Utilisateur créé un profil sur l’Application ou le Site.</p>
                    <p>Lors de la création du profil, l’Utilisateur doit renseigner une adresse email et créer un mot de passe. Les autres informations éventuellement demandées, ne le sont qu’à titre facultatif. </p>
                    <p>L’Utilisateur reste libre de modifier l’adresse email et le mot de passe associé à tout moment. </p>
                    <p>Par la suite, l’Utilisateur pourra accéder à son profil en saisissant ses identifiants et mot de passe associé.</p>
                    <p>L’Utilisateur a la charge de s’assurer de la confidentialité de son mot de passe et reste seul responsable de l’utilisation qu’il en fait. Toute connexion à son compte sera présumée être faite par lui.</p>

                    <h2>Article 4 - Responsabilité de l’Utilisateur</h2>
                    <p>Chaque Utilisateur est entièrement responsable de son utilisation des Applications ou des Sites, a fortiori s’il a créé un profil Utilisateur, et se doit d’en faire une utilisation conforme à leur destination dans le respect et la courtoisie qui s’impose à lui vis-à-vis des tiers.</p>
                    <p>Ainsi, lorsqu’un Utilisateur accès aux Application ou aux Sites, via un compte Utilisateur ou non, il s’interdit, entre autres, de :</p>
                    <ul>
                        <li>Enfreindre les lois et la réglementation applicable ;</li>
                        <li>
                            Reproduire ou diffuser des contenus :
                            <ul>
                                <li>sur lesquels il ne dispose d’aucun droit ;</li>
                                <li>injurieux, diffamatoires, portant atteinte à la vie privée de tout individu, contraire aux bonnes mœurs, ou, de manière générale, à caractère délictuel ou susceptibles de porter atteinte aux droits de tiers ; </li>
                                <li>susceptibles de porter atteinte à l’image de Fantasy King et de ses activités, ou de tout tiers ;</li>
                                <li>publicitaires sans l’autorisation préalable et explicite de Fantasy King ;</li>
                            </ul>
                        </li>
                        <li>Uploader vers les Sites ou les Applications ou d’utiliser des éléments pouvant contenir des logiciels, codes ou fichiers malveillants, de quelque nature qu’ils soient ;</li>
                        <li>Perturber ou interrompre, ou de tenter de perturber ou d’interrompre, le fonctionnement des Sites ou des Applications ou des serveurs et réseaux sur lesquels ils s’appuient ;</li>
                        <li>Accéder ou de tenter d’accéder à des données dont il n’est pas destinataires, qu’elles soient stockées par Fantasy King ou ses partenaires ;</li>
                        <li>Accéder ou de tenter d’accéder aux comptes d’autres Utilisateurs, à des fins d’usurpation d’identité ou non.</li>
                    </ul>
                    <p>L’Utilisateur est seul responsable de toute perte ou dommage à son système informatique ainsi qu’à ses terminaux ou les pertes de données qui en résultent directement ou indirectement.</p>
                    <p>Les commentaires et tout autre contenu publiés par les Utilisateurs sur les Applications et Sites ne sont pas des conseils et ne pourront être invoqués comme tels.</p>
                    <p>Fantasy King ne pourra être tenu pour responsable de la pertinence de ces commentaires et publication et n’encourra aucune responsabilité en raison de la confiance accordée à ces informations par tout internaute se connectant aux Sites ou aux Applications, ou par tout tiers ayant été informés desdits contenus.</p>
                    <p>Fantasy King décline toute responsabilité quant aux opinions affichées par les Utilisateurs sur les Sites et les Applications.</p>

                    <h2>Article 5 - Disponibilité et sécurité</h2>
                    <p>Les Sites et Application sont par principe accessibles 24h/24h, 7j/7j, sauf interruption, programmée ou non, pour les besoins de leur maintenance, de leur modification ou en cas de Force Majeure.</p>
                    <p>Fantasy King ne pourra pas être tenu responsable de tout dommage, quelle qu’en soit la nature, résultant d’une indisponibilité des Applications ou Sites.</p>
                    <p>L’Utilisateur est en outre informé du fait qu’en raison des caractéristiques intrinsèquement liées à l’internet, les données transmises via l’Application ne sont pas protégées contre les risques de détournement et/ou de piratage, ce dont Fantasy King ne saurait en aucun cas être tenu responsable. Il appartient à l’Utilisateur, le cas échéant, de prendre toutes les mesures appropriées de façon à protéger son ordinateur et/ou ses données, en ce compris par la mise en place des outils techniques adéquats, tels que par exemple, antivirus, pare-feu, outil de nettoyage automatique, etc.</p>

                    <h2>Article 6 - Liens vers les Sites et Applications</h2>
                    <p>La publication de liens vers les Sites et Application n’est soumise à aucun accord préalable mais reste néanmoins soumise au respect des présentes CGU ainsi que de la législation applicable.</p>
                    <p>Le lien doit par ailleurs ouvrir un nouvel onglet ou une nouvelle fenêtre et ne pas recourir à la technique dite de ‘framing’.</p>
                    <p>Enfin, Fantasy King se réserve le droit d’exiger la suppression d’un lien qu’il jugerait en contradiction avec l’objet des Sites ou des Applications ou portant atteinte à ses droits ou à ceux des tiers.</p>

                    <h2>Article 7 - Liens vers des sites et applications tierces</h2>
                    <p>Des liens vers d’autres sites internet ou des applications tierces peuvent être proposés sur les Sites ou Application. Fantasy King n’exerçant aucun contrôle sur le fonctionnement ou les publications de ces autres sites internet et applications tierces, Fantasy King décline toute responsabilité quant à d’éventuels dommages qui pourraient résulter de leur utilisation par les Utilisateurs. Pareillement, Fantasy King ne pourra être tenu pour responsable pour les publications faites par ces sites et applications ou tout traitement de données réalisés par eux.</p>

                    <h2>Article 8 - Suppression et suspension d’un compte Utilisateur</h2>
                    <p>Tout Utilisateur peut demander la suppression de son compte en ouvrant l’onglet dédié de son profil, puis en suivant la procédure qui y est détaillée. Autrement, la suppression du compte peut être demandé par l’envoi d’un email au Service Utilisateur dont l’adresse est indiquée à l’article 14 des présentes.</p>
                    <p>Fantasy King pourra aussi décider de la suspension ou suppression pure et simple d’un compte utilisateur, de manière discrétionnaire, s’il estime que l’Utilisateur a enfreint les présentes CGU ou la Politique de Confidentialité applicable. L’Utilisateur sera notifié de la suspension et de la durée de cette dernière ou de la suppression de son compte par email envoyé à l’adresse renseignée lors de la création de son profil. La notification indiquera la ou les raisons motivant la décision prise par Fantasy King. Les décisions de suspension ou de suppression de compte ne sont susceptibles d’aucun recours.</p>

                    <h2>Article 9 - Propriété intellectuelle</h2>
                    <p>Les Applications et les Sites ainsi que les éléments qui les composent sont la propriété exclusive de Fantasy King.</p>
                    <p>L’ensemble des éléments (textes, images, photographies, vidéos, logos, dessins, marques, brevets, bases de données, noms de domaine, etc.) présents sur l’Application et tout autre site internet ou application assimilé détenu par Fantasy King ou dont Fantasy King est bénéficiaire est protégé par le droit de la propriété intellectuelle, notamment par le droit d’auteur et le droit des marques.</p>
                    <p>Nul n’est autorisé à exploiter, diffuser ou utiliser les droits de propriété intellectuelle y compris, sans que cela soit limitatif, les droits détenus sur les noms Fantasy King et King of Paddock, sans l’accord préalable écrit de Fantasy King.</p>
                    <p>King of Paddock et KOP sont des marques déposées, tout comme le logo King Of Paddock.</p>
                    <p>Toute reproduction, utilisation ou exploitation des droits de propriété intellectuelle non-autorisée constituerait une contrefaçon et pourra faire l’objet d’une action en justice, notamment afin d’obtenir des dommages et intérêts.</p>
                    <p>De plus, l’Utilisateur ne pourra reproduire, modifier, transmettre, diffuser, traduire et de manière générale exploiter, commercialement ou non, tout ou Partie des Application et Sites et des éléments les composant.</p>
                    <p>L’Utilisateur reconnaît explicitement que toute publication de sa part sur les Applications ou Sites entraîne l’octroi d’une licence non-exclusive, gracieuse, irrévocable, cessible et transmissibles des droits de propriété intellectuelle permettant à Fantasy King de les reproduire, représente et adapter de quelque manière que ce soit.</p>

                    <h2>Article 10 - Responsabilité – Force majeure</h2>
                    <p>Fantasy King ne pourra être tenue pour responsable des conséquences résultant de la modification, de la discontinuité ou de la suppression des Sites ou Applications ou de toute fonctionnalité proposée par ces derniers, pour quelque raison que ce soit et notamment en raison de contraintes techniques.</p>
                    <p>Fantasy King ne pourra en aucun cas être tenue pour responsable des dommages que les Utilisateurs pourraient subir en naviguant sur ou se connectant aux Sites ou Applications et notamment, de tout virus ou logiciel malveillant qui pourraient être transmis.</p>
                    <p>En outre, tout événement pouvant être qualifié d’acte de force majeure (ci-après « Force Majeure ») pourra conduire à la suspension ou l’interruption des Sites ou Applications. La responsabilité de Fantasy King ne pourra être engagée lors d’un événement de Force Majeure. </p>
                    <p>La Force Majeure comprend tout fait ou omission dus à des circonstances irrésistibles, imprévisibles et indépendants de la volonté de Fantasy King, y compris, entre autres cas de Force Majeure, actions des autorités civiles ou militaires, incendies, intempéries de type gel, tempêtes, inondations, catastrophes naturelles, ou coupure générale de réseau électrique, coupure de l’accès routier ou interdiction de circulation.</p>
                    <p>Les obligations de Fantasy King, en vertu des présentes CGU seront automatiquement suspendues durant la période au cours de laquelle le Cas de force majeure se poursuit.
                    Fantasy King mettra tout en œuvre pour remédier aux inconvénients causés par le Cas de force majeure et pour trouver une solution lui permettant d’exécuter ses obligations contractuelles dans les meilleurs délais.</p>

                    <h2>Article 11 - Données personnelles</h2>
                    <p>Les CGU sont complétées par la Politique de Confidentialité accessible à l’adresse suivante : <A href={PRIVACY_PAGE}>https://app.kingofpaddock.com/politique-de-confidentialite</A></p>

                    <h2>Article 12 - Modification des CGU</h2>
                    <p>Fantasy King pourra modifier les présentes CGU à sa discrétion.</p>
                    <p>L’Utilisateur ne peut pas modifier ou réviser les CGU et aucune modification des CGU que l’Utilisateur tenterait d’effectuer n’engage Fantasy King.</p>
                    <p>L’Utilisateur sera lié par les CGU en vigueur au moment où il accède à l’Application ou au Site.</p>

                    <h2>Article 13 - Droit applicable – Réclamations – Règlement des différends</h2>
                    <p>Les présentes CGU sont régies par le droit français.</p>
                    <p>En cas de différend concernant ces CGU ou l’utilisation des Sites ou Applications, l’Utilisateur est invité à soumettre sa réclamation au Service Utilisateur.</p>
                    <p>Si l’Utilisateur n’est pas satisfait de la réponse apportée à sa réclamation ou si aucune réponse ne lui a été adressée dans un délai de deux (2) mois, il peut saisir de sa réclamation le médiateur dans les conditions décrites à l’adresse suivante : <Link href="https://ec.europa.eu/consumers/odr/main/?event=main.adr.show2" target="_blank" className="link">https://ec.europa.eu/consumers/odr/main/?event=main.adr.show2</Link></p>
                    <p>L’Utilisateur peut également saisir le médiateur compétent grâce à la plateforme de la Commission Européenne pour la résolution des litiges accessible à l’adresse suivante : <Link href="http://ec.europa.eu/consumers/odr/" target="_blank" className="link">http://ec.europa.eu/consumers/odr/</Link></p>
                    <p>L’Utilisateur peut porter son litige devant les juridictions françaises compétentes dès la survenance du différend ou si aucun accord amiable n’est trouvé.</p>


                    <h2>Article 14 - Service Utilisateur</h2>
                    <p>L’Utilisateur peut contacter Fantasy King en envoyant un courrier électronique à <A href="mailto:contact@king-of-paddock.com">contact@king-of-paddock.com</A>.</p>

                    <h2>Article 15 - Clause de non-responsabilité</h2>
                    <p>L’Application et le Site ne sont pas officiel et ne sont en aucun cas associés aux sociétés de Formule 1. F1, FORMULA ONE, FORMULA 1, FIA FORMULA ONE WORLD CHAMPIONSHIP, GRAND PRIX et les marques associées sont des marques commerciales de Formula One Licensing BV.</p>

                </Block>
            </Container>
        </main>
    );
}
