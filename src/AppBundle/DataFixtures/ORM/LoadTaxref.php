<?php

namespace AppBundle\DataFixtures\ORM;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;


use AppBundle\Entity\Ordre;
use AppBundle\Entity\Famille;
use AppBundle\Entity\Rang;
use AppBundle\Entity\Habitat;
use AppBundle\Entity\Auteur;
use AppBundle\Entity\Taxon;


class LoadTaxref implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $data = $serializer->decode(file_get_contents('G:\wamp64\www\professeur_tit\web\assets\csv\taxrefv2.csv'), 'csv');

        /*
        * Creation de la liste des Auteurs et intégration dans la base
        */
        $listeAuteurs = array_values(array_unique(array_column($data, 'AUTEURS')));
        $laListe = "";

        foreach ($listeAuteurs as $cle => $laValeur) {
            if ($laValeur != "") {
                if (strstr($laValeur, "&")) {
                    $test = explode(" & ", $laValeur);
                    foreach ($test as $newCle => $newValeur) {
                        if ($cle == 1) {
                            $laListe .= ucwords(strtolower($newValeur));
                        }
                        else {
                            $laListe = $laListe . " ; " . ucwords(strtolower($newValeur));
                        }
                    }
                } else {
                    if ($cle == 1) {
                        $laListe .= ucwords(strtolower($laValeur));
                    }
                    else {
                        $laListe = $laListe . " ; " . ucwords(strtolower($laValeur));
                    }
                }
            }
        }

        $listeAuteurs = array_values(array_unique(explode(" ; ", $laListe)));

               foreach ($listeAuteurs as $cle => $cetteValeur) {
                    $auteur = new Auteur();
                    $auteur->setName($cetteValeur);
                    $manager->persist($auteur);
                    $auteurs[$cetteValeur] = $auteur;
                }

        $manager->flush();

        /*
        * Creation de la liste des Ordres et intégration dans la base
        */

        $listeOrdres = array_values(array_unique(array_column($data, 'ORDRE')));

        foreach ($listeOrdres as $cle => $laValeur) {
            if ($laValeur != "") {
                $ordre = new Ordre();
                $ordre->setName($laValeur);
                $manager->persist($ordre);
                $ordres[$laValeur] = $ordre;
            }
        }

        $manager->flush();

        /*
        * Creation de la liste des Familles et intégration dans la base
        */

        $listeFamillesOrdres = array_column($data, 'ORDRE', 'FAMILLE');

        foreach ($listeFamillesOrdres as $cle => $laValeur) {
            $famille = new Famille();
            $famille->setOrdre($ordres[$laValeur]);
            $famille->setName($cle);
            $manager->persist($famille);
            $familles[$laValeur] = $famille;
        }
        $manager->flush();

        /*
        * Creation de la liste des Rangs et intégration dans la base
        */

        $listeRangs = array_values(array_unique(array_column($data, 'RANG')));

        foreach ($listeRangs as $cle => $laValeur) {
            if ($laValeur != "") {
                $rang = new Rang();
                $rang->setName($laValeur);
                $manager->persist($rang);
                $rangs[$laValeur] = $rang;
            }
        }
        $manager->flush();

        /*
        * Creation de la liste des Habitats et intégration dans la base
        */

        $listeHabitats = array_values(array_unique(array_column($data, 'HABITAT')));

        foreach ($listeHabitats as $cle => $laValeur) {
            if ($laValeur != "") {
                $habitat = new Habitat();
                $habitat->setName($laValeur);
                $manager->persist($habitat);
                $habitats[$laValeur] = $habitat;
            }
        }
        $manager->flush();

        /*
        * Creation des taxons et intégration dans la base
        */
        foreach ($data as $key => $value) {
            $taxon = new Taxon();
            if ($value['ORDRE']) {
                $lOrdre = $ordres[$value['ORDRE']];
                $laFamille = $familles[$value['ORDRE']];
                $taxon->setOrdre($lOrdre);
                $taxon->setFamille($laFamille);
            }

            if ($value['HABITAT']) {
                $lHabitat = $habitats[$value['HABITAT']];
                $taxon->setHabitat($lHabitat);
            }

            if ($value['RANG']) {
                $leRang = $rangs[$value['RANG']];
                $taxon->setRang($leRang);
            }

            if ($value['ANNEE']) {
                $taxon->setAnnee($value['ANNEE']);
            }

            if ($value['CD_TAXSUP']) {
                $taxon->setTaxSup($value['CD_TAXSUP']);
            }

            if ($value['CD_SUP']) {
                $taxon->setCdSup($value['CD_SUP']);
            }

            if ($value['AUTEURS']) {
               $test = explode("&", $value['AUTEURS']);
               foreach ($test as $newKey => $newValue) {
                   for ($i=0; $i<count($test); $i++) {
                       if (isset($auteurs[$newValue])) {
                            $lAuteur = $auteurs[$newValue];
                            $taxon->addAuteur($lAuteur);
                       }
                   }
               }
             }

            $taxon->setCdNom($value['CD_NOM']);
            $taxon->setCdRef($value['CD_REF']);
            $taxon->setNomLatin($value['LB_NOM']);
            $taxon->setNomVernaculaire($value['NOM_VERN']);
            $taxon->setNomVernaculaireEN($value['NOM_VERN_ENG']);
            $taxon->setUrlINPN($value['URL-INPN']);

            $manager->persist($taxon);
            $manager->flush();
        }

   }
}