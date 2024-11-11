<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
{
    private $enquiries;

    public function __construct($enquiries)
    {
        $this->enquiries = $enquiries;
    }

    /**
     * Prepare data as array for export
     */
    public function array(): array
    {
        $data = [];

        foreach ($this->enquiries as $enquiry) {
            $row = [];
            $row[] = ($enquiry->company_name ?? '') . "\n" .
                     \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y | h:i:s A');

            $row[] = $enquiry->category_name;

            $row[] = $enquiry->is_limited == 0 ? 'Normal' : 'Limited Enquiry';

            if ($enquiry->is_replied == 1){
                $row[] = 'Replied';
            }else{
                $row[] = $enquiry->expired_at > now() ? 'Expired' : 'Open';
            }

            $data[] = $row;
        }

        return $data;
    }

    /**
     * Define headings
     */
    public function headings(): array
    {
        return [
            'Enquiry Received',
            'Enquiry Categorized',
            'Type of Enquiry',
            'Enquiry Status',
        ];
    }

    /**
     * Register events to style the sheet
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Freeze the header row
                $sheet->freezePane('A2');

                // Style header row
                $sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'DFE0E6'], // Gray background for headers
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Enable text wrapping and center alignment for all cells
                $sheet->getStyle("A1:D" . $sheet->getHighestRow())->applyFromArray([
                    'alignment' => [
                        'wrapText' => true,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Apply borders to all cells
                $sheet->getStyle("A1:D" . $sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}