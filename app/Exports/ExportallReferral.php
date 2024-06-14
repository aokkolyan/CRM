<?php

namespace App\Exports;

use DB;
use App\Models\Referral;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


use LengthException;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportallReferral implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    use Exportable;
    public function headings(): array
    {
        return [
            "#",
            "referrer",
            "referral",
            "lead",
            "discussion",
            "rejected",
            "enrolled",
            "revenue",
            "commission",
            "paid_commission",
            "unpaid_commission",


        ];
    }
    // public function startCell(): string
    // {
    //     return 'A2';
    // }
     // public function collection2()
    // {
    //     // return Referral::all();
    //     $ExportallReferral = User::get()->map(function ($item) {
    //         return [

    //             '#' => $item->id,
    //             'referrer' =>  $item->name,
    //             'referral' => $item->referrals->count(),
    //             'lead'    => $item->referrals->where('referral_status_id', 1)->count(),
    //             'discussion' => $item->referrals->where('referral_status_id', 2)->count(),
    //             'rejected' => $item->referrals->where('referral_status_id', 3)->count(),
    //             'enrolled' => $item->referrals->where('referral_status_id', 4)->count(),
    //             'revenue'  => '$' . $item->referrals->sum('fee',0),
    //             'commission' => '$' . $item->referrals->sum('commission', 2),
    //             'paid_commission' => '$'. $item->referrals->where('payment_status_id',2)->sum('commission',2) ,
    //             'unpaid_commission' => '$'. $item->referralsInMyBranchApprovedNotYetPaid->sum('commission', 2),
    //         ];
    //     });

    //     return $ExportallReferral;
    // }
    public function collection()
    {
       
        $ExportallReferral = User::withCount('referrals')->with('referrals','referralsInMyBranchApprovedNotYetPaid')
        ->whereHas('referrals')
        ->get()->map(function ($item) {
            return [
                
                '#' => $item->id,
                'referrer' =>  $item->name,
                'referral' => $item->referrals->count()==0?'0':$item->referrals->count(),
                'lead'    => $item->referrals->where('referral_status_id', 1)->count()==0?'0':$item->referrals->where('referral_status_id', 1)->count(),
                'discussion' => $item->referrals->where('referral_status_id', 2)->count()==0?'0':$item->referrals->where('referral_status_id', 2)->count(),
                'rejected' => $item->referrals->where('referral_status_id', 3)->count()==0?'0':$item->referrals->where('referral_status_id', 3)->count(),
                'enrolled' => $item->referrals->where('referral_status_id', 4)->count()==0?'0':$item->referrals->where('referral_status_id', 4)->count(),
                'revenue'  => $item->referrals->sum('fee',0)==0?'0':$item->referrals->sum('fee',0),
                'commission' => $item->referrals->sum('commission', 2)==0?'0':$item->referrals->sum('commission', 2),
                'paid_commission' => $item->referrals->where('payment_status_id',2)->sum('commission',2)==0?'0':$item->referrals->where('payment_status_id',2)->sum('commission',2),
                'unpaid_commission' => $item->referralsInMyBranchApprovedNotYetPaid->sum('commission', 2)==0?'0':$item->referralsInMyBranchApprovedNotYetPaid->sum('commission', 2),
            ];
        });

        return $ExportallReferral;
    }
    public function columnFormats(): array
    {
        return [
            
            // 'H' => NumberFormat::FORMAT_CURRENCY_PHP_SIMPLE
            'H' => '$#,##0.00', // This formats the 'amount' column as currency
            'I' => '$#,##0.00', // This formats the 'amount' column as currency
            'J' => '$#,##0.00', // This formats the 'amount' column as currency
            'K' => '$#,##0.00', // This formats the 'amount' column as currency
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return  [
            1    => ['font' => ['bold' => true]],
        ];
    }
    public function registerEvents(): array
    {
        $referrals = Referral::all();
        $cellFormat = new NumberFormat();
        $cellFormat->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        return [
            AfterSheet::class => function (AfterSheet $event) use ($referrals) {
                $footerRow = User::withCount('referrals')->with('referrals')
                ->whereHas('referrals')
                ->count()+2; // Adjust the row number as needed
                $event->sheet->setCellValue('A' . $footerRow, 'Total:'); // Adyd your footer data here
                // You can set the styles for the footer row here if needed
                $event->sheet->setCellValue('C' . $footerRow, $referrals->count());
                $event->sheet->setCellValue('D' . $footerRow, $referrals->where('referral_status_id', 1)->count());
                $event->sheet->setCellValue('E' . $footerRow, $referrals->where('referral_status_id', 2)->count());
                $event->sheet->setCellValue('F' . $footerRow, $referrals->where('referral_status_id', 3)->count());
                $event->sheet->setCellValue('G' . $footerRow, $referrals->where('referral_status_id', 4)->count());
                $event->sheet->setCellValue('H' . $footerRow, $referrals->sum('fee'));
                $event->sheet->getStyle('H' . $footerRow)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $event->sheet->setCellValue('I' . $footerRow, $referrals->where('referral_status_id', 4)->sum('commission'));
                $event->sheet->getStyle('I' . $footerRow)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $event->sheet->setCellValue('J' . $footerRow, $referrals->where('payment_status_id',2)->sum('commission'));
                $event->sheet->getStyle('J' . $footerRow)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $event->sheet->setCellValue('K' . $footerRow, $referrals->where('payment_status_id',1)->sum('commission'));
                $event->sheet->getStyle('K' . $footerRow)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
            },

        ];
      
    }
    // public function columnFormats(): array
    // {
    //     return [
    //         'A' => NumberFormat::FORMAT_TEXT,
    //         'B' => NumberFormat::FORMAT_NUMBER,
    //         'C' => NumberFormat::FORMAT_TEXT,
    //         'D' => NumberFormat::FORMAT_CURRENCY_PHP_SIMPLE
    //         'E' => NumberFormat::FORMAT_TEXT,

    //     ];
    // }
    public function withBinders(): array
    {
        return [
            DecimalValueBinder::class,
        ];
    }
    
}
