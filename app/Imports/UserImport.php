<?php

namespace App\Imports;

use Throwable;
use App\Models\Role;
use App\Models\User;
use App\Models\Branch;
use App\Models\School;
use App\Models\RoleUser;
use App\Models\UserType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel,SkipsOnError,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       
        $school=School::where('code',$row['school'])->first();
        if(is_null($school)){
            $school=School::create([
                'name_en'=>$row['school'],
                'name_kh'=>$row['school'],
                'code'=>$row['school'],
            ]);
        }
        
     
        $branch=Branch::where('code',$row['branch'])->orWhere('name_en',$row['branch'])->first();
        if(is_null($branch)){
            $branch=Branch::create([
                'name_en'=>$row['branch'],
                'name_kh'=>$row['branch'],
                'code'=>$row['branch'],
                'school_id'=> $school?$school->id:0,
            ]);
        }
        $role=Role::where('name',$row['role'])->orWhere('value',$row['role'])->first();
        $user_type=UserType::where('name_en',$row['user_type'])->first();
        if($branch){
            $name=explode(' ', $row['name']);
            $user=User::where('user_name',$row['staff_id'])->first();

            $email=$row['email']??($name[1].'.'.$name[0].'@wegcambodia.com');
            if($user) return;
            $userWithThisEmail=User::where('email',$email)->first();
            if($userWithThisEmail) $email=($name[1].'.'.$name[0].'1@wegcambodia.com');
            $user=User::create([
                'name' => $row['name'].'',
                'name_kh' => $row['name_kh']??' ',
                'user_name' =>  $row['staff_id'],
                'password'=> Hash::make($row['staff_id']),
                'email' =>  $email,
                'school_id' =>  $school?$school->id:0,
                'branch_id' =>  $branch?$branch->id:0,
                'user_type_id' => $user_type['id'],
            ]);
            return new RoleUser([
                'user_id' => $user->id,
                'role_id' =>  $role?$role->id:3,
            ]);
        }
        
        // $user_type=UserType::where('name_en',$row['user_type'])->first();
        // if(is_null($user_type)){
        //     $user_type=UserType::create([
        //         'name_en'=>$row['user_type'],
        //         'name_kh'=>$row['user_type'],
        //         'user_type_id' => $user_type['id'],
        //         'branch_id' =>  $branch?$branch->id:0,
        //         'school_id'=> $school?$school->id:0,
        //     ]);
        // }
    }
    public function onError(Throwable $error){
        dd($error);
    }
}
