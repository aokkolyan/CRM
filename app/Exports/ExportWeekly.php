<?php

namespace App\Exports;


use App\Models\WeeklyReportReferral;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ExportWeekly implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            "branch",
            "prepared_by",
            "checked_by",
            "approved_by",
            "created_at",
            "remark",
        ];
    }
    public function collection()
    {
        $weeklyReports = WeeklyReportReferral::get()->map(function ($item) {
            return [
              
                'branch_name' => $item->branch->name_en,
                'prepared_by' => $item->preparer->name,
                'checked_by' => $item->checker ? $item->checker->name : 'Not checked',
                'approved_by' => $item->approver ? $item->approver->name : 'Not approver',
                'created_at' => $item->created_at->format('d/m/Y'),
                'remark'    => $item->remark,

            ];
        });
        return $weeklyReports;
        // dd($weeklyReports);
    }
}
