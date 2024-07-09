<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class SalesExport implements FromView
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $enquiries = DB::table('enquiries')
            ->join('enquiry_relations', 'enquiry_relations.enquiry_id', '=', 'enquiries.id')
            ->join('companies','companies.id','=','enquiries,company_id')
            ->where('enquiry_relations.to_id', '=', 27)
            ->whereBetween('enquiries.created_at', [$this->startDate, $this->endDate])
            ->select('enquiries.created_at', 'enquiries.company_id','companies.name as company_name', 'enquiries.sub_category_id', 'enquiries.is_limited')
            ->get();

        return view('exports.enquiries', compact('enquiries'));
    }
}
