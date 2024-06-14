<?php

namespace App\Exports;

use DB;
use App\Models\Referral;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

use LengthException;

class ExportallReferral implements FromCollection ,WithHeadings , WithStyles, WithEvents
{
    
    public function headings(): array
    {
        return [
            "#",
            "referrer",
            "referral",
            "lead",
            "discussion",
            "enrolled",
            "rejected",
            "revenue",
            "commission",
            "unpaid_commission",
           
           
        ];
    }
    // public function startCell(): string
    // {
    //     return 'A2';
    // }
    public function collection()
    {
        // return Referral::all();
        $ExportallReferral = User::get()->map(function($item) {
            return [
             
                '#'=>$item->id,
                'referrer' =>  $item->name,
                'referral'=> $item->referrals->count(),
                'lead'    => $item->referrals->where('referral_status_id',1)->count(),
                'discussion'=> $item->referrals->where('referral_status_id',2)->count(),
                'rejected' => $item->referrals->where('referral_status_id',3)->count(),
                'enrolled' => $item->referrals->where('referral_status_id',4)->count(),
                'revenue'  => $item->referrals->sum('fee'),
                'commission' => $item->referrals->sum('commission'),
                'unpaid_commission' => $item->referralsInMyBranchApprovedNotYetPaid->sum('commission'),  
              
                
            ];
          
        });
       
      return $ExportallReferral;
      //dd( $ExportallReferral);
    }
    public function styles(Worksheet $sheet)
    {
        return  [
            1    => ['font' => ['bold' => true]],
        ];
       
    }
    public function registerEvents(): array
    {
        $referrals=Referral::all();
        return [
            AfterSheet::class => function (AfterSheet $event) use($referrals) {
                $footerRow = User::count() + 2; // Adjust the row number as needed

                $event->sheet->setCellValue('A' . $footerRow, 'Total:'); // Add your footer data here
                // You can set the styles for the footer row here if needed
                $event->sheet->setCellValue('C' . $footerRow,  $referrals->count());
                $event->sheet->setCellValue('D' . $footerRow, $referrals->where('referral_status_id',1)->count());
                //lead//
                $event->sheet->setCellValue('E' . $footerRow, $referrals->where('referral_status_id',2)->count());
                //dicussion//
                $event->sheet->setCellValue('G' . $footerRow, $referrals->where('referral_status_id',3)->count());
                //enrolled//
                $event->sheet->setCellValue('F' . $footerRow,$referrals->where('referral_status_id',4)->count());
                $event->sheet->setCellValue('H' . $footerRow, $referrals->sum('fee'));
                $event->sheet->setCellValue('I' . $footerRow,  $referrals->sum('commission'));
                $event->sheet->setCellValue('J' . $footerRow, 'j');
            },
        ];
    }

}
