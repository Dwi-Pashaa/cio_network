<?php

namespace App\Exports;

use App\Models\MacAddress;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MacAddressLabelExport implements FromCollection, WithStyles
{
    public function collection()
    {
        // Return blank agar tidak otomatis menulis raw data list ke bawah dan menimpa layout grid kita di bawah.
        return collect([]);
    }

    public function styles(Worksheet $sheet)
    {
        // Hilangkan garis bantu bawaan excel agar bersih pas dicetak
        $sheet->setShowGridlines(false);

        $row = 1;
        $col = 1;

        $macAddresses = MacAddress::where('organization_id', auth()->user()->organization_id)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($macAddresses as $item) {
            $startCell = $sheet->getCellByColumnAndRow($col, $row)->getCoordinate();
            $endCell   = $sheet->getCellByColumnAndRow($col + 2, $row + 1)->getCoordinate();
            $range     = $startCell . ':' . $endCell;

            $sheet->mergeCells($range);
            $sheet->setCellValueByColumnAndRow($col, $row, strtoupper($item->mac_address));

            $sheet->getStyle($range)->applyFromArray([
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'bold' => true,
                    'size' => 15,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            $sheet->getRowDimension($row)->setRowHeight(20);
            $sheet->getRowDimension($row + 1)->setRowHeight(20);

            for ($i = $col; $i <= $col + 2; $i++) {
                $sheet->getColumnDimensionByColumn($i)->setWidth(11);
            }

            $col += 4; // Beri jarak 1 kolom kosong antar label ke kanan

            // Maksimal 3 label ke samping
            if ($col > 12) {
                $col = 1;
                $row += 3; // Beri jarak 1 baris kosong ke bawah
            }
        }
    }
}
