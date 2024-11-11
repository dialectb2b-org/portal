<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class EnquiriesExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            $row[] = $enquiry['reference_no'] . "\n" .
                     ($enquiry['sub_category']['name'] ?? '') . "\n" .
                     ($enquiry['sender']['email'] ?? '') . "\n" .
                     \Carbon\Carbon::parse($enquiry['created_at'])->format('d-m-Y | h:i:s A');

            $row[] = $enquiry['is_limited'] == 0 ? 'Normal' : 'Limited Enquiry';

            $participants = [];
            foreach ($enquiry['all_replies'] as $reply) {
                $participants[] = ($reply['sender']['company']['name'] ?? '') . "\n" .
                                  \Carbon\Carbon::parse($reply['created_at'])->format('d-m-Y | h:i:s A');
            }
            $row[] = implode("\n", $participants);

            $row[] = count($enquiry['action_replies']) . " : " . count($enquiry['all_replies']);

            $statuses = [];
            foreach ($enquiry['all_replies'] as $reply) {
                if ($reply['status'] == 0 && $reply['is_read'] == 0) {
                    $statuses[] = $reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null ? 'Recommanded' : 'Unread';
                } elseif ($reply['status'] == 0 && $reply['is_read'] == 1) {
                    $statuses[] = $reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null ? 'Recommanded' : 'TBD';
                } elseif ($reply['status'] == 1) {
                    $statuses[] = $reply['is_selected'] == 1 && $enquiry['shared_to'] !== null ? 'Selected' : 'Shortlisted';
                } elseif ($reply['status'] == 2) {
                    $statuses[] = $reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null ? 'Recommanded' : 'On Hold';
                } elseif ($reply['status'] == 3) {
                    $statuses[] = 'Proceed';
                }
            }
            $row[] = implode("\n", $statuses);

            $row[] = in_array('Proceed', $statuses) ? 'Proceed' : '';

            $row[] = $enquiry['shared_to'] ? ($enquiry['shared']['name'] ?? '') . ' at ' . ($enquiry['shared']['email'] ?? '') . "\n" .
                     \Carbon\Carbon::parse($enquiry['shared_at'])->format('d-m-Y | h:i:s A') : '';

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
            'Enquiry',
            'Type Of Enquiry',
            'List Of Participants',
            'Screening Ratio',
            'Screening Status',
            'Bid Rating',
            'Status',
            'Enquiry Status',
            'Conclusion',
            'Shared To',
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
                $sheet->getStyle('A1:J1')->applyFromArray([
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
                $sheet->getStyle("A1:J" . $sheet->getHighestRow())->applyFromArray([
                    'alignment' => [
                        'wrapText' => true,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Apply borders to all cells
                $sheet->getStyle("A1:J" . $sheet->getHighestRow())->applyFromArray([
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