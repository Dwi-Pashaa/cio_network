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
        return MacAddress::select('mac_address')->get();
    }

    public function styles(Worksheet $sheet)
    {
        $row = 1;
        $col = 1;

        foreach (MacAddress::all() as $item) {
            $sheet->mergeCellsByColumnAndRow($col, $row, $col + 5, $row + 1);
            $sheet->setCellValueByColumnAndRow($col, $row, strtoupper($item->mac_address));

            $sheet->getStyleByColumnAndRow($col, $row)->applyFromArray([
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
                'font' => [
                    'bold' => true,
                    'size' => 20,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                    ],
                ],
            ]);

            $col += 6;
            if ($col > 18) {
                $col = 1;
                $row += 2;
            }
        }
    }
}
