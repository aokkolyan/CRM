<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ExportUser implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            "name",
            "email",
            "Phone",
        ];
    }
    public function collection()
    {
        return User::select('name', 'email', 'phone')->get();

        // $users = DB::table('users')
        // ->join('branches', 'users.id', '=' , 'branches.id')
        // ->join('weekly_report_referrals', 'users.id' , '=' , 'weekly_report_referrals.id')
        // ->select('users.*','branches.name_en' , 'weekly_report_referrals.branch_id')
        // ->get();
        // dd($users);
    }
}
