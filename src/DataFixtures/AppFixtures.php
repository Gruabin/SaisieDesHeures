<?php

namespace App\DataFixtures;
use Ramsey\Uuid\Uuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CentreDeCharge;
use App\Entity\Employe;
use Doctrine\DBAL\Connection;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créez des objets CentreDeCharge
        $centreDeCharge1 = new CentreDeCharge();
        $centreDeCharge1->setId("LV0001000");

        $centreDeCharge2 = new CentreDeCharge();
        $centreDeCharge2->setId("LV0002000");

        // Création de plusieurs employés fictifs
        $employe1 = new Employe();
        $employe1->setId('LV0000001');
        $employe1->setNomEmploye('Test Employe');
        $employe1->setRoles(['ROLE_EMPLOYE']);
        $employe1->setCentreDeCharge($centreDeCharge1);
        $manager->persist($employe1);

        $employe2 = new Employe();
        $employe2->setId('LV0000002');
        $employe2->setNomEmploye('Test Responsable');
        $employe2->setRoles(['ROLE_MANAGER']);
        $employe2->setCentreDeCharge($centreDeCharge1);
        $manager->persist($employe2);

        $employe3 = new Employe();
        $employe3->setId('LV0000003');
        $employe3->setNomEmploye('Test Responsable 2');
        $employe3->setRoles(['ROLE_MANAGER']);
        $employe3->setCentreDeCharge($centreDeCharge2);
        $manager->persist($employe3);

        // Associez un responsable à ce centre de charge
        $centreDeCharge1->setResponsable($employe2);
        $centreDeCharge2->setResponsable($employe3);

        // Persistez l'objet CentreDeCharge
        $manager->persist($centreDeCharge1);
        $manager->persist($centreDeCharge2);

        $manager->flush();

        // Démarrez une transaction pour les insertions SQL natives
        $connection = $manager->getConnection();
        $connection->beginTransaction(); // Démarrer une transaction

        try {
            // Insertion des données de la table statut en bdd
            $sql = "INSERT INTO statut (id, libelle) VALUES (:id, :libelle)";
            $stmt = $connection->prepare($sql);
            $statutsData = $this->getStatutData();
            foreach ($statutsData as $statutData) {
                $stmt->bindValue('id', $statutData['id']);
                $stmt->bindValue('libelle', $statutData['libelle']);
                $stmt->executeStatement();
            }

            // Exécutez des requêtes SQL natives pour insérer vos données
            $sql = "INSERT INTO activite (id, description_activite) VALUES (:id, :description)";
            $stmt = $connection->prepare($sql);
            $activitesData = $this->getActiviteData();
            foreach ($activitesData as $activiteData) {
                $stmt->bindValue('id', $activiteData['id']);
                $stmt->bindValue('description', $activiteData['description']);
                $stmt->executeStatement();
            }

            // Exécutez des requêtes SQL natives pour insérer vos données
            $sql = "INSERT INTO site (id) VALUES (:id)";
            $stmt = $connection->prepare($sql);
            $sitesData = $this->getSitesData();
            foreach ($sitesData as $siteData) {
                $stmt->bindValue('id', $siteData['id']);
                $stmt->executeStatement();
            }

              // Exécutez des requêtes SQL natives pour insérer vos données
              $sql = "INSERT INTO type_heures (id,nom_type) VALUES (:id,:nom_type)";
              $stmt = $connection->prepare($sql);
              $typesHeuresData = $this->getTypesHeuresData();
              foreach ($typesHeuresData as $typeHeureData) {
                  $stmt->bindValue('id', $typeHeureData['id']);
                  $stmt->bindValue('nom_type', $typeHeureData['nom_type']);
                  $stmt->executeStatement();
              }

              // Exécutez des requêtes SQL natives pour insérer vos données
              $sql = "INSERT INTO tache (id,nom_tache,type_heures_id) VALUES (:id,:nom_tache,:type_heures_id)";
              $stmt = $connection->prepare($sql);
              $tachesData = $this->getTachesData();
              foreach ($tachesData as $tacheData) {
                  $stmt->bindValue('id', $tacheData['id']);
                  $stmt->bindValue('nom_tache', $tacheData['nom_tache']);
                  $stmt->bindValue('type_heures_id', $tacheData['type_heures_id']);
                  $stmt->executeStatement();
              }
 
               // Exécutez des requêtes SQL natives pour insérer vos données
               $sql = "INSERT INTO tache_specifique (id,description) VALUES (:id,:description)";
               $stmt = $connection->prepare($sql);
               $tachesSpecifiquesData = $this->getTachesSpecifiquesData();
               foreach ($tachesSpecifiquesData as $tacheSpecifiqueData) {
                   $stmt->bindValue('id', $tacheSpecifiqueData['id']);
                   $stmt->bindValue('description', $tacheSpecifiqueData['description']);
                   $stmt->executeStatement();
               }

            // Exécutez des requêtes SQL natives pour insérer vos données
            $sql = "INSERT INTO site_tache_specifique (site_id,tache_specifique_id) VALUES (:site_id,:tache_specifique_id)";
            $stmt = $connection->prepare($sql);
            $sitesTachesSpecifiquesData = $this->getSitesTachesSpecifiquesData();
            foreach ($sitesTachesSpecifiquesData as $siteTacheSpecifiqueData) {
                $stmt->bindValue('site_id', $siteTacheSpecifiqueData['site_id']);
                $stmt->bindValue('tache_specifique_id', $siteTacheSpecifiqueData['tache_specifique_id']);
                $stmt->executeStatement();
            }
        

            $detailsHeuresData = $this->getDetailsHeuresData();
            // Prepare the SQL statement without the id column
            $sql = "INSERT INTO detail_heures (id,type_heures_id, tache_id, activite_id, centre_de_charge_id, employe_id, tache_specifique_id, statut_id, date, temps_main_oeuvre, operation, ordre, date_export, motif_erreur) VALUES (:id,:type_heures_id, :tache_id, :activite_id, :centre_de_charge_id, :employe_id, :tache_specifique_id, :statut_id, :date, :temps_main_oeuvre, :operation, :ordre, :date_export, :motif_erreur)";
            $stmt = $connection->prepare($sql);
            $detailsHeuresData = $this->getDetailsHeuresData();
            foreach ($detailsHeuresData as $detailHeureData) {
                $stmt->bindValue('id', $detailHeureData['id']);
                $stmt->bindValue('type_heures_id', $detailHeureData['type_heures_id']);
                $stmt->bindValue('tache_id', $detailHeureData['tache_id']);
                $stmt->bindValue('activite_id', $detailHeureData['activite_id']);
                $stmt->bindValue('centre_de_charge_id', $detailHeureData['centre_de_charge_id']);
                $stmt->bindValue('employe_id', $detailHeureData['employe_id']);
                $stmt->bindValue('tache_specifique_id', $detailHeureData['tache_specifique_id']);
                $stmt->bindValue('statut_id', $detailHeureData['statut_id']);
                $stmt->bindValue('date', $detailHeureData['date']);
                $stmt->bindValue('temps_main_oeuvre', $detailHeureData['temps_main_oeuvre']);
                $stmt->bindValue('operation', $detailHeureData['operation']);
                $stmt->bindValue('ordre', $detailHeureData['ordre']);
                $stmt->bindValue('date_export', $detailHeureData['date_export']);
                $stmt->bindValue('motif_erreur', $detailHeureData['motif_erreur']);

                $stmt->executeStatement();
            }

            // Validez la transaction
            $connection->commit();
        } catch (\Exception $e) {
            // En cas d'erreur, annulez la transaction
            $connection->rollBack();
            throw $e;
        }
        // Persiste les données dans la base de données
        $manager->flush();
    }

    public function getActiviteData(): array
    {
      return  $activitesData = [
            ['id' => 100, 'description' => "ETUDES"],
            ['id' => 101, 'description' => "ETUDES-Amélioration continue"],
            ['id' => 102, 'description' => "ETUDES-Compléments de projet inf. à 20 H"],
            ['id' => 110, 'description' => "ETUDES-Carrosserie"],
            ['id' => 120, 'description' => "ETUDES-Electrique"],
            ['id' => 130, 'description' => "ETUDES-Mécatronique"],
            ['id' => 140, 'description' => "ETUDES-Pilotage"],
            ['id' => 150, 'description' => "ETUDES-Innovation"],
            ['id' => 160, 'description' => "ETUDES-Recherche"],
            ['id' => 170, 'description' => "ETUDES-Design"],
            ['id' => 180, 'description' => "ETUDES-Maquettages"],
            ['id' => 200, 'description' => "Methodes"],
            ['id' => 201, 'description' => "METHODES-Amélioration Continue"],
            ['id' => 202, 'description' => "METHODES-Remplacement outillage externe"],
            ['id' => 203, 'description' => "METHODES-Modification outillage externe"],
            ['id' => 204, 'description' => "METHODES-Changement de fournisseur"],
            ['id' => 205, 'description' => "METHODES-Demande de modif. constructeur"],
            ['id' => 206, 'description' => "METHODES-Vie série PR et MR constructeur"],
            ['id' => 207, 'description' => "METHODES-Modification implantation"],
            ['id' => 210, 'description' => "METHODES-Methodes industrielles"],
            ['id' => 220, 'description' => "METHODES-Documentation technique"],
            ['id' => 300, 'description' => "PROTOTYPES"],
            ['id' => 301, 'description' => "PROTOTYPES-Contrôle, métrologie"],
            ['id' => 302, 'description' => "PROTOTYPES-Essai pour l interne et fourniss."],
            ['id' => 303, 'description' => "PROTOTYPES-Homologation"],
            ['id' => 304, 'description' => "PROTOTYPES-Prototypage inf. à 20 H"],
            ['id' => 305, 'description' => "PROTOTYPES-Contrôle métrol. programmé"],
            ['id' => 310, 'description' => "PROTOTYPES-Métallerie"],
            ['id' => 311, 'description' => "PROTOTYPES-Métallerie-Découpage"],
            ['id' => 312, 'description' => "PROTOTYPES-Métallerie-Mise en forme"],
            ['id' => 313, 'description' => "PROTOTYPES-Métallerie-Profilage"],
            ['id' => 314, 'description' => "PROTOTYPES-Métallerie-Ferrage"],
            ['id' => 320, 'description' => "PROTOTYPES-Mécanique"],
            ['id' => 321, 'description' => "PROTOTYPES-Mécanique-Usinage mécanique"],
            ['id' => 322, 'description' => "PROTOTYPES-Mécanique-Automatisme"],
            ['id' => 330, 'description' => "PROTOTYPES-Maquettage"],
            ['id' => 331, 'description' => "PROTOTYPES-Maquettage-Menuiserie"],
            ['id' => 332, 'description' => "PROTOTYPES-Maquettage-Modelage"],
            ['id' => 333, 'description' => "PROTOTYPES-Maquettage-Plasturgie"],
            ['id' => 334, 'description' => "PROTOTYPES-Maquettage-Prototypage"],
            ['id' => 340, 'description' => "PROTOTYPES-Traitement"],
            ['id' => 341, 'description' => "PROTOTYPES-Traitement-Revêtement"],
            ['id' => 342, 'description' => "PROTOTYPES-Traitement-Laquage"],
            ['id' => 343, 'description' => "PROTOTYPES-Traitement-Décoration"],
            ['id' => 350, 'description' => "PROTOTYPES-Sellerie"],
            ['id' => 351, 'description' => "PROTOTYPES-Sellerie-Garnissage"],
            ['id' => 352, 'description' => "PROTOTYPES-Sellerie-Sellerie Gruau"],
            ['id' => 360, 'description' => "PROTOTYPES-Carrosserie"],
            ['id' => 361, 'description' => "PROTOTYPES-Carrosserie-Proto probatoire"],
            ['id' => 362, 'description' => "PROTOTYPES-Carrosserie-Démonstrateur"],
            ['id' => 363, 'description' => "PROTOTYPES-Carrosserie-Inovation"],
            ['id' => 370, 'description' => "PROTOTYPES-Mécatronique"],
            ['id' => 371, 'description' => "PROTOTYPES-Mécatronique-Mécanique"],
            ['id' => 372, 'description' => "PROTOTYPES-Mécatronique-Electrique"],
            ['id' => 373, 'description' => "PROTOTYPES-Mécatronique-Integration divers"],
            ['id' => 374, 'description' => "PROTOTYPES-Mécatronique-Garage"],
            ['id' => 380, 'description' => "PROTOTYPES-Divers"],
            ['id' => 381, 'description' => "PROTOTYPES-Divers-Nettoyage"],
            ['id' => 382, 'description' => "PROTOTYPES-Divers-Recyclage"],
            ['id' => 400, 'description' => "ESSAIS/METROLOGIE"],
            ['id' => 410, 'description' => "ESSAIS/METROLOGIE-Métrologie"],
            ['id' => 411, 'description' => "ESSAIS/METROLOGIE-Métrologie-Contrôle"],
            ['id' => 412, 'description' => "ESSAIS/METROLOGIE-Métrologie-Rétro conception"],
            ['id' => 413, 'description' => "ESSAIS/METROLOGIE-Métrologie-Homologation"],
            ['id' => 420, 'description' => "ESSAIS/METROLOGIE-Essais"],
            ['id' => 421, 'description' => "ESSAIS/METROLOGIE-Essais-Essais divers"],
            ['id' => 422, 'description' => "ESSAIS/METROLOGIE-Essais-Homologation"],
            ['id' => 423, 'description' => "ESSAIS/METROLOGIE-Essais-PIV Synthèse"],
            ['id' => 424, 'description' => "ESSAIS/METROLOGIE-Essais-PIV Organique"],
            ['id' => 430, 'description' => "ESSAIS/METROLOGIE-Divers"],
            ['id' => 500, 'description' => "OUTILLAGES"],
            ['id' => 501, 'description' => "OUTILLAGES-Amélioration Continue"],
            ['id' => 502, 'description' => "OUTILLAGES-Maintenance outillages"],
            ['id' => 503, 'description' => "OUTILLAGES-Maintenance conteneurs"],
            ['id' => 504, 'description' => "OUTILLAGES-Plaquage outillage"],
            ['id' => 505, 'description' => "OUTILLAGES-Contrôle métrologique programmé"],
            ['id' => 510, 'description' => "OUTILLAGES-Outillage"],
            ['id' => 511, 'description' => "OUTILLAGES-Outillage-Usinage mécanique"],
            ['id' => 512, 'description' => "OUTILLAGES-Outillage-Assemblage"],
            ['id' => 513, 'description' => "OUTILLAGES-Outillage-Automatisme"],
            ['id' => 514, 'description' => "OUTILLAGES-Outillage-Validation outillage"],
            ['id' => 520, 'description' => "OUTILLAGES-Outillage-Divers"],
            ['id' => 600, 'description' => "FABRICATION"],
            ['id' => 601, 'description' => "FABRICATION-Prototypes"]];
    }


    public function getSitesData():array {
        return $sitesData = [
          ['id' => "AC"],
          ['id' => "AL"],
          ['id' => "AM"],
          ['id' => "AP"],
          ['id' => "LB"],
          ['id' => "LM"],
          ['id' => "LV"],
          ['id' => "LY"],
          ['id' => "PN"],
          ['id' => "SM"],
      ];
      }

    public function getSitesTachesSpecifiquesData(): array
    {
        return $sitesTachesSpecifiquesData = [
            ['site_id' => "AC", 'tache_specifique_id' => "ACT902"],
            ['site_id' => "AC", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "AC", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "AL", 'tache_specifique_id' => "ALT902"],
            ['site_id' => "AL", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "AL", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "AM", 'tache_specifique_id' => "AMT902"],
            ['site_id' => "AM", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "AM", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "AP", 'tache_specifique_id' => "APT902"],
            ['site_id' => "AP", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "AP", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT210"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT220"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT221"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT222"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT223"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT224"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT225"],
            ['site_id' => "LB", 'tache_specifique_id' => "LBT528"],
            ['site_id' => "LB", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "LM", 'tache_specifique_id' => "LMT203"],
            ['site_id' => "LM", 'tache_specifique_id' => "LMT902"],
            ['site_id' => "LM", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "LV", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "LV", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "LV", 'tache_specifique_id' => "LVT902"],
            ['site_id' => "LY", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "LY", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "LY", 'tache_specifique_id' => "LYT902"],
            ['site_id' => "PN", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "PN", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "PN", 'tache_specifique_id' => "PNT902"],
            ['site_id' => "SM", 'tache_specifique_id' => "LVT203"],
            ['site_id' => "SM", 'tache_specifique_id' => "LVT206"],
            ['site_id' => "SM", 'tache_specifique_id' => "SMT902"],
        ];
    }

    public function getStatutData(): array
    {
        return $statutsData =[
            ['id' => 1, 'libelle' => "Enregistré"],
            ['id' => 2, 'libelle' => "Anomalie"],
            ['id' => 3, 'libelle' => "Conforme"],
            ['id' => 4, 'libelle' => "Approuvé"],
            ['id' => 5, 'libelle' => "Traité"],
            ['id' => 6, 'libelle' => "Supprimé"],
        ];
    }

    public function getTachesData(): array
    {
        return $tachesData = [
            ['id' => 1, 'nom_tache' => "ETUDES", 'type_heures_id' => 4],
            ['id' => 2, 'nom_tache' => "METHODES", 'type_heures_id' => 4],
            ['id' => 3, 'nom_tache' => "PROTOTYPES", 'type_heures_id' => 4],
            ['id' => 4, 'nom_tache' => "ESSAIS", 'type_heures_id' => 4],
            ['id' => 5, 'nom_tache' => "OUTILLAGES", 'type_heures_id' => 4],
            ['id' => 6, 'nom_tache' => "PRODUCTION", 'type_heures_id' => 4],
            ['id' => 100, 'nom_tache' => "MANAGEMENT", 'type_heures_id' => 1],
            ['id' => 101, 'nom_tache' => "TRAVAUX DE GROUPE", 'type_heures_id' => 1],
            ['id' => 102, 'nom_tache' => "FORMATION AU POSTE", 'type_heures_id' => 1],
            ['id' => 103, 'nom_tache' => "PLAN DE FORMATION", 'type_heures_id' => 1],
            ['id' => 104, 'nom_tache' => "INFO GENERALE", 'type_heures_id' => 1],
            ['id' => 105, 'nom_tache' => "CSE HRS DELEGATION CAPN", 'type_heures_id' => 1],
            ['id' => 106, 'nom_tache' => "INFIRMERIE", 'type_heures_id' => 1],
            ['id' => 107, 'nom_tache' => "PAUSE EQUIPE", 'type_heures_id' => 1],
            ['id' => 108, 'nom_tache' => "RANGEMENT", 'type_heures_id' => 1],
            ['id' => 109, 'nom_tache' => "GESTION ADMINISTRATIVE CC", 'type_heures_id' => 1],
            ['id' => 110, 'nom_tache' => "ABSENCE", 'type_heures_id' => 1],
            ['id' => 111, 'nom_tache' => "PRET PERSONNEL HORS FAB", 'type_heures_id' => 1],
            ['id' => 112, 'nom_tache' => "AMELIORATION POUR LA QUALITE", 'type_heures_id' => 1],
            ['id' => 113, 'nom_tache' => "AMELIORATION CONTINUE: QRQC,", 'type_heures_id' => 1],
            ['id' => 114, 'nom_tache' => "DEMERITE QUALITE", 'type_heures_id' => 1],
            ['id' => 115, 'nom_tache' => "CONTROLE / ESSAI", 'type_heures_id' => 1],
            ['id' => 116, 'nom_tache' => "CONTROLE HOMOLOGATION", 'type_heures_id' => 1],
            ['id' => 117, 'nom_tache' => "RECETTE CLIENT", 'type_heures_id' => 1],
            ['id' => 118, 'nom_tache' => "INVENTAIRE", 'type_heures_id' => 1],
            ['id' => 119, 'nom_tache' => "ENTRETIEN MATERIEL PRODUCTION", 'type_heures_id' => 1],
            ['id' => 120, 'nom_tache' => "ENTRETIEN MATERIEL DIVERS", 'type_heures_id' => 1],
            ['id' => 121, 'nom_tache' => "ENTRETIEN BATIMENT", 'type_heures_id' => 1],
            ['id' => 122, 'nom_tache' => "PREPARATION SALON", 'type_heures_id' => 1],
            ['id' => 124, 'nom_tache' => "RECEPTION PARC", 'type_heures_id' => 1],
            ['id' => 125, 'nom_tache' => "PRET DE PERSONNEL FILIALE", 'type_heures_id' => 1],
            ['id' => 126, 'nom_tache' => "VISITE MEDICALE", 'type_heures_id' => 1],
            ['id' => 127, 'nom_tache' => "ARRET PROD EVENMNT EXCEPTIONL", 'type_heures_id' => 1],
            ['id' => 200, 'nom_tache' => "PREVENTIF PROCESS", 'type_heures_id' => 1],
            ['id' => 201, 'nom_tache' => "CURATIF PROCESS", 'type_heures_id' => 1],
            ['id' => 202, 'nom_tache' => "AMELIORATION PROCESS", 'type_heures_id' => 1],
            ['id' => 203, 'nom_tache' => "ASSISTANCE PRODUCTION", 'type_heures_id' => 1],
            ['id' => 204, 'nom_tache' => "CONFORMITE PROCESS", 'type_heures_id' => 1],
            ['id' => 205, 'nom_tache' => "PREVENTIF BATIMENT", 'type_heures_id' => 1],
            ['id' => 206, 'nom_tache' => "CURATIF BATIMENT", 'type_heures_id' => 1],
            ['id' => 207, 'nom_tache' => "AMENAGEMENT BATIMENT", 'type_heures_id' => 1],
            ['id' => 208, 'nom_tache' => "SERVICE", 'type_heures_id' => 1],
            ['id' => 209, 'nom_tache' => "CONFORMITE BATIMENT", 'type_heures_id' => 1],
            ['id' => 210, 'nom_tache' => "PROJET INDUSTRIEL", 'type_heures_id' => 1],
            ['id' => 211, 'nom_tache' => "MAINTENANCE AUTONOME (1er niv)", 'type_heures_id' => 1],
            ['id' => 300, 'nom_tache' => "GESTION SYSTEME CAO", 'type_heures_id' => 1],
            ['id' => 301, 'nom_tache' => "PILOTAGE DES PROJETS", 'type_heures_id' => 1],
            ['id' => 302, 'nom_tache' => "OUTILLAGES", 'type_heures_id' => 1],
            ['id' => 303, 'nom_tache' => "POST CONSO DES HEURES", 'type_heures_id' => 1],
            ['id' => 304, 'nom_tache' => "CREATION PROG MACHINE OUTIL", 'type_heures_id' => 1],
            ['id' => 305, 'nom_tache' => "PROGRAMMATION CREATION/MODIF", 'type_heures_id' => 1],
            ['id' => 306, 'nom_tache' => "PROGRAMMATION PLIAGE", 'type_heures_id' => 1],
            ['id' => 307, 'nom_tache' => "ETUDES SPECIFIQUES <1h", 'type_heures_id' => 1],
            ['id' => 308, 'nom_tache' => "PROTOTYPE", 'type_heures_id' => 1],
            ['id' => 309, 'nom_tache' => "PREPARATION INVENTAIRE", 'type_heures_id' => 1],
            ['id' => 310, 'nom_tache' => "CONTROLE RECEPTION MP PAR MOD", 'type_heures_id' => 1],
            ['id' => 400, 'nom_tache' => "PROJET ENTREPRISE", 'type_heures_id' => 1],
            ['id' => 401, 'nom_tache' => "PILOTAGE TERRAIN", 'type_heures_id' => 1],
            ['id' => 402, 'nom_tache' => "AUTRES TACHES ANIMATEUR GAP", 'type_heures_id' => 1],
            ['id' => 403, 'nom_tache' => "PANNE MACHINE", 'type_heures_id' => 1],
        ];
    }

    public function getTachesSpecifiquesData(): array
    {
        return $tachesSpecifiquesData = [
            ['id' => "ACT902", 'description' => "Formation au poste"],
            ['id' => "ALT902", 'description' => "Formation au poste"],
            ['id' => "AMT902", 'description' => "Formation au poste"],
            ['id' => "APT902", 'description' => "Formation au poste"],
            ['id' => "LBT210", 'description' => "Reprise NQI"],
            ['id' => "LBT220", 'description' => "Reprise NQI PSO"],
            ['id' => "LBT221", 'description' => "Reprise NQI LAMBALLE"],
            ['id' => "LBT222", 'description' => "Reprise NQI PLESTAN"],
            ['id' => "LBT223", 'description' => "Reprise NQI Peinture"],
            ['id' => "LBT224", 'description' => "Reprise NQI Décoration"],
            ['id' => "LBT225", 'description' => "Reprise NQI Départ-Réception"],
            ['id' => "LBT528", 'description' => "Formation au poste"],
            ['id' => "LMT203", 'description' => "NQI"],
            ['id' => "LMT902", 'description' => "Formation au poste"],
            ['id' => "LVT203", 'description' => "NQI"],
            ['id' => "LVT206", 'description' => "Rupture matière"],
            ['id' => "LVT902", 'description' => "Formation au poste"],
            ['id' => "LYT902", 'description' => "Formation au poste"],
            ['id' => "PNT902", 'description' => "Formation au poste"],
            ['id' => "SMT902", 'description' => "Formation au poste"],
        ];
    }

    public function getTypesHeuresData(): array
    {
        return $typesHeuresData =[
            ['id' => 1, 'nom_type' => "Générale"],
            ['id' => 2, 'nom_type' => "Fabrication"],
            ['id' => 3, 'nom_type' => "Service"],
            ['id' => 4, 'nom_type' => "Projet"],

        ];
    }

    public function getDetailsHeuresData(): array
    {
        return $detailsHeuresData = [
            [
                'id' => Uuid::uuid4(),
                'type_heures_id' => 1,
                'ordre' => null,
                'tache_id' => 200,
                'activite_id' => null,
                'centre_de_charge_id' => null,
                'date' => '2024-04-19 16:02:48',
                'temps_main_oeuvre' => 0.50,
                'employe_id' => "LV0000002",
                'operation' => null,
                'date_export' => null,
                'tache_specifique_id' => null,
                'motif_erreur' => null,
                'statut_id' => 3,
            ],[
                'id' => Uuid::uuid4(),
                'type_heures_id' => 2,
                'ordre' => "ABC123465",
                'tache_id' => null,
                'activite_id' => null,
                'centre_de_charge_id' => null,
                'date' => '2024-04-22 16:02:48',
                'temps_main_oeuvre' => 1.00,
                'employe_id' => "LV0000002",
                'operation' => null,
                'date_export' => null,
                'tache_specifique_id' => null,
                'motif_erreur' => null,
                'statut_id' => 2,
            ],
            [
                'id' => Uuid::uuid4(),
                'type_heures_id' => 4,
                'ordre' => "LV1234678",
                'tache_id' => 1,
                'activite_id' => 200,
                'centre_de_charge_id' => null,
                'date' => '2024-04-22 11:02:48',
                'temps_main_oeuvre' => 2.00,
                'employe_id' => "LV0000003",
                'operation' => null,
                'date_export' => null,
                'tache_specifique_id' => null,
                'motif_erreur' => null,
                'statut_id' => 2,
            ],
        ];
    }

}
