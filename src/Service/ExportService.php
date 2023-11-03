<?php

namespace App\Service;

use App\Repository\DetailHeuresRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @property DetailHeuresRepository $detailHeuresRepository
 */
class ExportService
{
    public function __construct(
        DetailHeuresRepository $detailHeuresRepository
    ) {
        $this->detailHeuresRepository = $detailHeuresRepository;
    }

    public function exportExcel(string $file = 'php://output'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Saisie des heures');
        $this->exportDetailHeure($spreadsheet);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        // création de la réponse streamée
        $response = new StreamedResponse(function () use ($writer, $file) {
            $writer->save($file);
        });

        // définition des en-têtes HTTP
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="[findchem][export].xlsx"');

        return $response;
    }

    private function exportDetailHeure(Spreadsheet $spreadsheet): void
    {
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $this->exportHeader($sheet);
        $this->exportItems($sheet);
    }

    private function exportHeader(Worksheet $sheet): void
    {
        $x = 1;
        $color = 'FFC107';
        $this->setStyleHeader($sheet, $x++, 'Type d\'heure', $color);
        $this->setStyleHeader($sheet, $x++, 'Ordre', $color);
        $this->setStyleHeader($sheet, $x++, 'Opération', $color);
        $this->setStyleHeader($sheet, $x++, 'Tâche', $color);
        $this->setStyleHeader($sheet, $x++, 'Centre de charge', $color);
        $this->setStyleHeader($sheet, $x++, 'Temps main d\'œuvre', $color);
    }

    /**
     * @throws Exception
     */
    private function exportItems(Worksheet $sheet): void
    {
        $items = $this->detailHeuresRepository->findAllToday();
        foreach ($items as $key => $item) {
            $x = 1;
            $y = $key + 2;

            $color = (0 == $y % 2) ? 'FFF8E1' : 'FFECB3';

            $value = '';
            if (!empty($item->getTypeHeures())) {
                $value = $item->getTypeHeures()->getNomType();
            }
            $this->setStyleItem($sheet, $x++, $y, $value, $color);

            $value = '';
            if (!empty($item->getOrdre())) {
                $value = $item->getOrdre()->getDescriptionOrdre();
            }
            $this->setStyleItem($sheet, $x++, $y, $value, $color);

            $value = '';
            if (!empty($item->getOperation())) {
                $value = $item->getOperation()->getDescriptionOperation();
            }
            $this->setStyleItem($sheet, $x++, $y, $value, $color);
            
            $value = '';
            if (!empty($item->getTache())) {
                $value = $item->getTache()->getNomTache();
            }
            $this->setStyleItem($sheet, $x++, $y, $value, $color);

            $value = '';
            if (!empty($item->getCentreDeCharge())) {
                $value = $item->getCentreDeCharge()->getDescriptionCDG();
            }
            $this->setStyleItem($sheet, $x++, $y, $value, $color);

            $value = '';
            if (!empty($item->getTempsMainOeuvre())) {
                $value = $item->getTempsMainOeuvre();    
            }
            $this->setStyleItem($sheet, $x++, $y, $value, $color);
        }
    }

    private function setStyleHeader(Worksheet $sheet, int $x, string $value, string $color): void
    {
        $y = 1;
        $x = $this->setStyleValue($x, $value, $sheet, $y, $color);
        $sheet
            ->getStyle($x . $y)
            ->getFont()
            ->setSize(12)
            ->setBold(true);
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['argb' => $color],
                ],
            ],
        ];
        $sheet
            ->getStyle($x . $y)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true)
            ->applyFromArray($styleArray);
    }

    /**
     * @param string|null $value
     */
    private function setStyleItem(Worksheet $sheet, int $x, int $y, mixed $value, string $color): void
    {
        $x = $this->setStyleValue($x, $value, $sheet, $y, $color);
        $sheet
            ->getStyle($x . $y)
            ->getFont()
            ->setSize(11)
            ->setBold(false);
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['argb' => $color],
                ],
            ],
        ];
        $sheet
            ->getStyle($x . $y)
            ->getAlignment()
            ->setWrapText(true)
            ->applyFromArray($styleArray);
        $sheet
            ->getColumnDimension($x)
            ->setAutoSize(true);
    }

    private function setStyleValue(int $x, mixed $value, Worksheet $sheet, int $y, string $color): string
    {
        $x = $this->decimalToAlphabetic($x);
        $sheet->setCellValue($x . $y, $value);
        $sheet
            ->getStyle($x . $y)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($color);

        return $x;
    }

    private function decimalToAlphabetic($decimal): string
    {
        $alphabetic = '';
        while ($decimal > 0) {
            $remainder = $decimal % 26;
            $decimal = floor($decimal / 26);
            if (0 == $remainder) {
                $remainder = 26;
                --$decimal;
            }
            $alphabetic = chr($remainder + 64) . $alphabetic;
        }

        return $alphabetic;
    }
}
