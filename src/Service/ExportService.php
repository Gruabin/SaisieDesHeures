<?php

namespace App\Service;

use App\Repository\DetailHeuresRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @property DetailHeuresRepository $detailHeuresRepository
 * @property Security               $security
 * @property EntityManagerInterface $entityManager
 */
class ExportService
{
    public function __construct(
        DetailHeuresRepository $detailHeuresRepository,
        Security $security,
        EntityManagerInterface $entityManager,
    ) {
        $this->detailHeuresRepository = $detailHeuresRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * Exporte les détails d'heures vers un fichier Excel et génère une réponse streamée pour le téléchargement.
     *
     * @param string $file le chemin du fichier de sortie (par défaut, 'php://output' pour la sortie directe)
     *
     * @return StreamedResponse la réponse streamée pour le téléchargement du fichier Excel
     */
    public function exportExcel(string $file = 'php://output'): StreamedResponse
    {
        // Crée un nouvel objet Spreadsheet et sa feuille de calcul
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Saisie des heures');

        // Exporte les détails d'heures dans la feuille de calcul
        $this->exportDetailHeure($spreadsheet);

        // Crée un écrivain pour le format Xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Crée une réponse streamée pour le téléchargement du fichier Excel
        $response = new StreamedResponse(function () use ($writer, $file) {
            $writer->save($file);
        });

        // Récupère l'utilisateur actuel
        $user = $this->security->getUser();

        // Définit les en-têtes HTTP pour la réponse
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="[Gruau][SaisiDesHeures]['.$user->getId().']['.date('Y-m-d').'].xlsx"');

        // Retourne la réponse streamée
        return $response;
    }

    /**
     * Exporte les détails d'heures vers la feuille de calcul en coordonnant les différentes parties de l'exportation.
     *
     * @param Spreadsheet $spreadsheet L'objet de la classe Spreadsheet représentant la feuille de calcul
     */
    private function exportDetailHeure(Spreadsheet $spreadsheet): void
    {
        // Sélectionne la première feuille de calcul
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        // Exporte l'en-tête, les éléments et les informations d'en-tête
        $this->exportHeader($sheet);
        $this->exportItems($sheet);
        $this->exportEntete($sheet);
    }

    /**
     * Exporte l'en-tête de la feuille de calcul avec le style approprié.
     *
     * @param Worksheet $sheet L'objet de la classe Worksheet représentant la feuille de calcul
     */
    private function exportHeader(Worksheet $sheet): void
    {
        // Initialisation de la position horizontale et couleur d'arrière-plan
        $x = 1;
        $color = 'FFC107';

        // Applique le style d'en-tête à chaque colonne
        $this->setStyleHeader($sheet, $x++, 'Date d\'enregistrement', $color);
        $this->setStyleHeader($sheet, $x++, 'Employé', $color);
        $this->setStyleHeader($sheet, $x++, 'Type d\'heure', $color);
        $this->setStyleHeader($sheet, $x++, 'Ordre', $color);
        $this->setStyleHeader($sheet, $x++, 'Opération', $color);
        $this->setStyleHeader($sheet, $x++, 'Activité', $color);
        $this->setStyleHeader($sheet, $x++, 'Tâche', $color);
        $this->setStyleHeader($sheet, $x++, 'Centre de charge', $color);
        $this->setStyleHeader($sheet, $x++, 'Temps main d\'œuvre en heures', $color);
    }

    /**
     * Exporte les informations d'en-tête dans la feuille de calcul.
     *
     * @param Worksheet $sheet L'objet de la classe Worksheet représentant la feuille de calcul
     */
    private function exportEntete(Worksheet $sheet): void
    {
        // Initialisation des positions horizontale et verticale
        $x = 1;
        $y = 1;

        // Récupération de l'utilisateur actuel
        $user = $this->security->getUser();

        // Applique le style d'item à chaque élément de l'en-tête
        $this->setStyleItem($sheet, $x++, $y, substr((string) $user->getId(), 0, 2));
    }

    /**
     * Exporte les détails d'heures dans la feuille de calcul en appliquant le style approprié.
     *
     * @param Worksheet $sheet L'objet de la classe Worksheet représentant la feuille de calcul
     */
    private function exportItems(Worksheet $sheet): void
    {
        // Récupère l'utilisateur actuel
        $user = $this->security->getUser();

        // Sélectionne les détails d'heures en fonction de l'utilisateur
        if (str_starts_with((string) $user->getId(), 'GA')) {
            $items = $this->detailHeuresRepository->findAllExport();
        } else {
            $items = $this->detailHeuresRepository->findAllExportSite();
        }

        // Parcourt les détails d'heures à exporter
        foreach ($items as $key => $item) {
            // Vérifie si l'élément n'a pas encore été exporté
            if (null == $item->getDateExport()) {
                $x = 1;
                $y = $key + 4;

                // Alterne la couleur de fond en fonction de la ligne
                $color = (0 == $y % 2) ? 'FFF8E1' : 'FFECB3';

                $value = '';
                if (!empty($item->getdate())) {
                    $value = $item->getdate()->format('d/m/Y');
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getEmploye())) {
                    $value = $item->getEmploye()->getId().' - '.$item->getEmploye()->getNomEmploye();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getTypeHeures())) {
                    $value = $item->getTypeHeures()->getNomType();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getOrdre())) {
                    $value = $item->getOrdre();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getOperation())) {
                    $value = $item->getOperation();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getActivite())) {
                    $value = $item->getActivite()->getName();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getTache())) {
                    $value = $item->getTache()->getName();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getCentreDeCharge())) {
                    $value = $item->getCentreDeCharge()->getId();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);

                $value = '';
                if (!empty($item->getTempsMainOeuvre())) {
                    $value = $item->getTempsMainOeuvre();
                }
                $this->setStyleItem($sheet, $x++, $y, $value, $color);
                $now = new \DateTime();
                $heure = \DateTime::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d H:i:s'));
                $heure->setTimezone(new \DateTimeZone('Europe/Paris'));
                $item->setDateExport($heure);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * Applique le style à l'en-tête d'une feuille de calcul.
     *
     * @param Worksheet $sheet L'objet de la classe Worksheet représentant la feuille de calcul
     * @param int       $x     la position horizontale de l'en-tête (index ou représentation alphabétique)
     * @param string    $value la valeur à définir dans l'en-tête
     * @param string    $color la couleur de remplissage de l'en-tête
     */
    private function setStyleHeader(Worksheet $sheet, int $x, string $value, string $color): void
    {
        // Position verticale fixe pour l'en-tête
        $y = 3;

        // Applique le style de valeur à l'en-tête
        $x = $this->setStyleValue($x, $value, $sheet, $y, $color);

        // Applique le style de police à l'en-tête
        $sheet
            ->getStyle($x.$y)
            ->getFont()
            ->setSize(12)
            ->setBold(true);

        // Définition du style de bordure pour l'en-tête
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['argb' => $color],
                ],
            ],
        ];

        // Applique le style d'alignement à l'en-tête
        $sheet
            ->getStyle($x.$y)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true)
            ->applyFromArray($styleArray);
    }

    /**
     * Applique le style à une cellule spécifique dans une feuille de calcul.
     *
     * @param Worksheet $sheet L'objet de la classe Worksheet représentant la feuille de calcul
     * @param int       $x     la position horizontale de la cellule (index ou représentation alphabétique)
     * @param int       $y     la position verticale de la cellule
     * @param mixed     $value la valeur à définir dans la cellule
     * @param string    $color la couleur de remplissage de la cellule (facultatif)
     */
    private function setStyleItem(Worksheet $sheet, int $x, int $y, mixed $value, ?string $color = null): void
    {
        // Applique le style de valeur à la cellule
        $x = $this->setStyleValue($x, $value, $sheet, $y, $color);

        // Applique le style de police à la cellule
        $sheet
            ->getStyle($x.$y)
            ->getFont()
            ->setSize(11)
            ->setBold(false);

        // Définition du style de bordure pour la cellule
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['argb' => $color],
                ],
            ],
        ];

        // Applique le style de bordure et d'alignement à la cellule
        $sheet
            ->getStyle($x.$y)
            ->getAlignment()
            ->setWrapText(true)
            ->applyFromArray($styleArray);

        // Ajuste la largeur de colonne automatiquement en fonction du contenu
        $sheet
            ->getColumnDimension($x)
            ->setAutoSize(true);
    }

    /**
     * Définit la valeur et le style d'une cellule dans une feuille de calcul.
     *
     * @param int       $x     la position horizontale de la cellule (index ou représentation alphabétique)
     * @param mixed     $value la valeur à définir dans la cellule
     * @param Worksheet $sheet L'objet de la classe Worksheet représentant la feuille de calcul
     * @param int       $y     la position verticale de la cellule
     * @param string    $color la couleur de remplissage de la cellule (facultatif)
     *
     * @return string la position horizontale de la cellule après conversion (représentation alphabétique)
     */
    private function setStyleValue(int $x, mixed $value, Worksheet $sheet, int $y, ?string $color = null): string
    {
        // Conversion de l'index horizontal en représentation alphabétique
        $x = $this->decimalToAlphabetic($x);

        // Définition de la valeur de la cellule
        $sheet->setCellValue($x.$y, $value);

        // Vérification de la présence d'une couleur de remplissage
        if (null == $color) {
            // Si pas de couleur spécifiée, applique un remplissage solide à la cellule
            $sheet
                ->getStyle($x.$y)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID);

            // Retourne la position horizontale de la cellule
            return $x;
        }

        // Si une couleur est spécifiée, applique un remplissage solide avec la couleur spécifiée à la cellule
        $sheet
            ->getStyle($x.$y)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($color);

        // Retourne la position horizontale de la cellule
        return $x;
    }

    /**
     * Convertit un nombre décimal en une représentation alphabétique basée sur les colonnes d'une feuille de calcul.
     *
     * @param int $decimal le nombre décimal à convertir
     *
     * @return string la représentation alphabétique correspondante
     */
    private function decimalToAlphabetic($decimal): string
    {
        $alphabetic = '';

        // Boucle tant que le nombre décimal est supérieur à zéro
        while ($decimal > 0) {
            // Calcul du reste de la division par 26 (nombre de lettres de l'alphabet)
            $remainder = $decimal % 26;

            // Division entière par 26 pour passer à la colonne suivante
            $decimal = floor($decimal / 26);

            // Ajustement du reste si nécessaire
            if (0 == $remainder) {
                $remainder = 26;
                --$decimal;
            }

            // Conversion du reste en caractère alphabétique et construction de la chaîne résultante
            $alphabetic = chr($remainder + 64).$alphabetic;
        }

        // Retourne la représentation alphabétique
        return $alphabetic;
    }
}
