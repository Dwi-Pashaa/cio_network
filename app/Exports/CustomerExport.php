<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $orgType = optional($user->organization)->type;

        $query = Customer::with(['router', 'type', 'hometown', 'rt', 'rw', 'village', 'district', 'regencie', 'vlan', 'odc', 'odp', 'olt', 'paket', 'price'])
            ->orderBy('id', 'DESC');

        if ($orgType === 'mitra') {
            $query->where('organization_id', $user->organization_id);
        }

        $orgName = optional($user->organization)->name ?? 'SEMUA CABANG';

        return view('exports.customer', [
            'customers' => $query->get(),
            'orgName' => $orgName
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $activeRow = $sheet->getHighestRow();
        $activeCol = $sheet->getHighestColumn();

        // Style untuk Title (Row 1)
        $sheet->getStyle('A1:' . $activeCol . '1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Style untuk Header (Row 2)
        $sheet->getStyle('A2:' . $activeCol . '2')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color'    => ['argb' => 'FF4F46E5'], // Primary indigo color
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Style Border & Vertical Center untuk seluruh tabel dimulai dari baris 2
        $sheet->getStyle('A2:' . $activeCol . $activeRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFCBD5E1'], // Slate-300
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);
    }
}
