import React from "react";
import language from "@/messages/fr";
import { Container } from "@/components/custom/container";
import { Block } from "@/components/custom/block";
import { A } from "@/components/custom/link";
import { CGU_PAGE } from "@/constants/routing";

export default function About() {
    return (
        <main>
            <Container>
                <h1
                    className="h1"
                    dangerouslySetInnerHTML={{
                        __html: language.privacy.title,
                    }}
                ></h1>
            </Container>

            <Container className="mt-6 mb-6">
                <Block childClassName="p-4 pt-4 block-cms">
                    <i>Version en date du 02/07/2024</i>
                    <p className="mt-4"><b>Préambule</b></p>
                    <p>La présente politique de confidentialité (ci-après la « <b>Politique</b> ») vous informe sur la manière dont Fantasy King traite, en qualité de responsable de traitement, vos données à caractère personnel collectées lors de l’utilisation de la ou les applications mobiles et sites internet détenus par elle (le ou les « <b>Site(s)</b> » ou la ou les « <b>Application(s)</b> ») ainsi que de la fourniture des services relevant de l’activité commerciale de Fantasy King (ci-après les « <b>Services</b> »). </p>
                    <p>Fantasy King est une société par actions simplifiée, SIREN n° 948 716 915, immatriculée auprès du RCS de Vienne, domiciliée à La Loco’Working, 4 impasse des frères lumière, 38300 BOURGOIN JALLIEU (ci-après « <b>nous</b> » ou « <b>Fantasy King</b> »).</p>
                    <p>En utilisant l’Application, vous acceptez que Fantasy King puisse collecter certaines données à caractère personnel vous concernant. </p>
                    <p>La présente Politique met en œuvre les principes issus du règlement (UE) 2016/679 – règlement général sur la protection des données du 27 avril 2016 (« <b>RGPD</b> ») et de la loi n°78-17 du 6 janvier 1978 modifiée (« <b>Loi Informatique et Libertés</b> »).</p>

                    <h2>1. A propos de Fantasy King</h2>
                    <p>Fantasy King est une société commerciale dont l’objet est, entre autres, de commercialiser des jeux de ‘fantasy league’ via ses Sites et Applications.</p>
                    <p>Pour toutes informations relatives au fonctionnement des Sites ou Applications, nous vous invitons à consulter nos Conditions Générales d’Utilisation accessible à l’URL suivante : <A href={CGU_PAGE}>https://app.kingofpaddock.com/conditions-generales-utilisation</A></p>

                    <h2>2. Quelles données collectons-nous ?</h2>
                    <p>Dans le cadre de l’utilisation des Sites et Applications, via la création d’un compte en vue de pouvoir bénéficier des Services proposés, nous collectons des données à caractère personnel vous concernant, et notamment :</p>
                    <ul>
                        <li>Votre adresse email ;</li>
                        <li>Vos identifiants et mot de passe de connexion ;</li>
                        <li>Une photo de profil.</li>
                    </ul>
                    <p>Nous pouvons par ailleurs collecter vos nom et prénoms si vous souhaitez les renseigner.</p>

                    <h2>3. Pour quelles finalités et sur quelle base juridique traitons-nous vos données ? </h2>
                    <p>Nous traitons vos données à caractère personnel, via l’utilisation des Sites ou Applications et la fourniture des Services, pour les seules finalités listées ci-dessous et pour lesquelles nous avons identifié la base légale applicable conformément à la réglementation en vigueur : </p>

                    <table>
                        <tbody>
                            <tr>
                                <th></th>
                                <th>Finalités</th>
                                <th>Base légale</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td><p>Pour effectuer les opérations relatives à la gestion de la relation utilisateur et à la fourniture des Services, et pour répondre à toutes demandes d’informations ou réclamations.</p></td>
                                <td><p>Le traitement est nécessaire pour l’exécution du contrat conclu entre vous et Fantasy King et constitué par les CGU et les CGV en cas d’achat.</p></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><p>Pour améliorer les Services proposés (information technique, référencement des erreurs).</p></td>
                                <td><p>Nous procédons à un traitement de données sur la base de notre intérêt légitime en tant que responsable de traitement dans le respect de vos droits et libertés. Cela nous permet de nous assurer que nous poursuivons cette finalité sans porter atteinte ni à vos droits ni à vos libertés.</p></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><p>A des fins statistiques.</p></td>
                                <td><p>Nous procédons à un traitement de données sur la base de notre intérêt légitime en tant que responsable de traitement dans le respect de vos droits et libertés. Cela nous permet de nous assurer que nous poursuivons cette finalité sans porter atteinte ni à vos droits ni à vos libertés.</p></td>
                            </tr>
                        </tbody>
                    </table>


                    <h2>4. Avec qui partageons-nous vos données ?</h2>
                    <p>Nous partageons vos données à caractère personnel avec certains de nos prestataires de services qui agissent sur nos instructions (prestataires assurant la maintenance, le développement et l’hébergement des Sites et Applications, etc.) pour les seules finalités décrites dans la Politique.</p>

                    <h2>5. Combien de temps conservons-nous vos données ?</h2>
                    <p>Nous conservons vos données à caractère personnel le temps nécessaire à la réalisation des finalités exposées ci-dessus.</p>
                    <p>Dans le cadre de l’utilisation de vos données aux fins de gestion de la relation client, nous conservons vos données pendant une durée de trois (3) ans à compter de la suppression de votre compte ou à compter de votre dernière connexion avec ce compte sur les Sites ou Applications.</p>
                    <p>Dans le cadre d’une demande de renseignements ou d’une prise de contact avec Fantasy King, nous conservons vos données pendant une durée d’un (1) an à compter de votre dernière interaction avec les Services.</p>
                    <p>Lorsque vos données ne sont plus nécessaires, nous veillons strictement à ce qu’elles soient selon le cas supprimées, chiffrées ou rendues anonymes dans le cadre de la mise en œuvre d’analyses statistiques.</p>

                    <h2>6. Comment assurons-nous la sécurité et la confidentialité de vos données ?</h2>
                    <p>La protection de votre vie privée et la sécurité de vos données est un enjeu important pour Fantasy King. A cet égard, nous avons mis en œuvre des mesures techniques et organisationnelles afin d’éviter tout incident de nature à entraîner une perte, utilisation, altération ou mise à la disposition du public de vos données sans votre autorisation préalable.</p>
                    <p>Lorsque vous êtes inscrit sur les Sites ou Applications en vue de bénéficier des Services, nous mettons en œuvre les mesures de sécurité suivantes :</p>
                    <ul>
                        <li>vos données sont protégées sur un mode « end-to-end » (de bout en bout) au moyen d’un chiffrement SSL ;</li>
                        <li>les mots de passe sont cryptés et nous n’y avons pas accès.</li>
                    </ul>
                    <p>Nous ne stockons que les données strictement nécessaires au fonctionnement des Sites ou Applications. Nous mettons également en œuvre en interne des mesures de sécurité organisationnelles afin de limiter strictement le nombre de personnes ayant accès aux données que nous traitons au regard des seules finalités poursuivies.</p>
                    <p>Nous nous engageons à ne pas divulguer vos données et à ce qu’elles ne servent qu’au bon fonctionnement des Sites ou Applications.</p>


                    <h2>7. Vos données et les transferts hors UE/EEE </h2>
                    <p>Fantasy King ne procède à aucun transfert de données en dehors de la zone UE/EEE. </p>
                    <p>En revanche, les prestataires et éventuels sous-traitants auxquels nous faisons appel, peuvent être amenés à transférer des données en dehors de l’UE/EEE. Le cas échéant, nous nous assurons que ces transferts se font sur la base de garanties appropriées permettant d’assurer la sécurité de vos données. </p>

                    <h2>8. Quels sont vos droits et comment les exercer ?</h2>
                    <p>Aux termes du RGPD et de la Loi Informatique et Libertés, vous disposez de certains droits que vous pouvez exercer à tout moment, sous réserve de justifier de votre identité, en nous contactant à l’adresse suivante : contact@king-of-paddock.com</p>
                    <ul>
                        <li><b>Droit d’accès</b> : vous pouvez nous demander de confirmer si nous traitons vos données personnelles et, le cas échéant, obtenir des informations sur les caractéristiques du traitement. Si vous le souhaitez, vous pouvez également obtenir une copie de vos données traitées ;</li>
                        <li><b>Droit de rectification</b> : vous pouvez nous demander de rectifier ou de compléter vos données si elles sont incorrectes ou incomplètes le cas échéant ;</li>
                        <li><b>Droit à l’effacement</b> : vous pouvez nous demander d’effacer, dans certains cas, vos données ;</li>
                        <li><b>Droit à la limitation du traitement</b> : vous pouvez nous demander de limiter le traitement à la seule conservation de vos données à certaines conditions ;</li>
                        <li><b>Droit à la portabilité</b> : vous pouvez nous demander de vous fournir vos données dans un format structuré, couramment utilisé et lisible par une machine, ou qu’elles soient transmises directement à un autre responsable de traitement, mais uniquement si le traitement est fondé sur votre consentement ou sur l’exécution d’un contrat conclu avec vous ;</li>
                        <li><b>Droit d’opposition</b> : sous certaines conditions, vous pouvez vous opposer à la mise en œuvre d’opérations de traitement sur vos données.</li>
                    </ul>
                    <p>Vous disposez en outre du droit de définir des directives relatives au sort de vos données après votre décès ainsi que du droit d’introduire une réclamation auprès de la CNIL.</p>
                    <p>Enfin, nous vous informons que nous n’effectuons aucune prise de décision automatisée, y compris le profilage, à l’aide de vos données.</p>

                    <h2>9. Cookies</h2>
                    <p>L’utilisation des Sites ou Applications peut entraîner le dépôt d’un cookie sur votre équipement.</p>
                    <p>Un cookie est un fichier composé principalement de lettres et de chiffres, de taille limitée et stocké sur votre équipement.</p>
                    <p>Un cookie peut être implanté sur votre ordinateur ou votre téléphone portable afin notamment d’enregistrer des informations relatives à la navigation, dans le but d’améliorer les conditions techniques de navigation et l’expérience utilisateur.</p>
                    <p>Vous serez toujours informés avant l’implantation d’un cookie. En outre, votre consentement sera toujours requis avant l’implantation d’un cookie sauf s’il est strictement nécessaire aux services proposés sur le Site ou s’il a pour finalité exclusive de permettre ou faciliter la communication par voie électronique.</p>
                    <p>Les Sites ou Applications ont recours à des cookies qui sont strictement nécessaires à leur fonctionnement. Afin de les désactiver vous devrez paramétrer votre navigateur Internet ou votre téléphone portable à cet effet. Néanmoins, cela pourrait affecter le bon fonctionnement des Sites ou Applications concernés.</p>
                    <p>Les données collectées à l’aide de ces cookies sont conservées pendant une durée de vingt-quatre (24) mois maximum à compter de leur dépôt sur votre équipement.</p>

                    <h2>10. Modification de la Politique</h2>
                    <p>Fantasy King se réserve le droit de modifier cette Politique occasionnellement, notamment afin de prendre en compte toutes les évolutions législatives et réglementaires futures, ainsi que les bonnes pratiques. La présente Politique pourra également être modifiée afin de refléter tous les changements des conditions du traitement de vos données ainsi que pour toute autre raison que nous jugerions utile à notre discrétion.</p>
                    <p>Nous vous informerons de toutes modifications substantielles par tous moyens appropriés.</p>

                    <h2>11. Nous contacter</h2>
                    <p>Pour toute question ou réclamation relative à cette Politique, au traitement de vos données et à l’exercice de vos droits, vous pouvez nous contacter en écrivant à l’adresse suivante : <A href="mailto:contact@king-of-paddock.com">contact@king-of-paddock.com</A></p>

            </Block>
            </Container>
        </main>
    );
}
