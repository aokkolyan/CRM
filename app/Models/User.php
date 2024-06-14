<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'user_name',
        'phone',
        'status','created_by',
        'deleted_by',
        'default_password',
        'school_id',
        'branch_id',
        'name_kh',
        'user_type_id',
        'student_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * Belong  to role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->wherePivot('deleted_at', null)->withPivot('id');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function branches()
    {
        return $this->belongsToMany(Branch::class,'branch_configs','user_id','branch_id')->where('school_id','!=',1)->wherePivot('deleted_at', null)->withPivot('id')->orderBy('school_id');;
    }
    public function referrals()
    {
        return $this->hasMany(Referral::class,'created_by');
    }
    public function referralsInMyBranch()
    {
        return $this->hasMany(Referral::class,'created_by')->where('branch_id',Auth::user()->branch_id)->orWhereIn('branch_id',Auth::user()->branches->pluck('id'));
    }
    public function referralsInMyBranchApprovedNotYetPaid()
    {
        return $this->hasMany(Referral::class,'created_by')
            ->where(function ($query) {//in my branch only
                $query->where('branch_id',Auth::user()->branch_id)
                ->orWhereIn('branch_id',Auth::user()->branches->pluck('id'));
            })
            ->where('referral_status_id',4)//Inrolled
            ->where('payment_status_id',1);
            //not yet paid
            // ->whereHas('report', function ($query) { //has been approved
            //     $query->whereNotNull('approved_by');
            // });
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function commissionType()
    {
        return $this->belongsTo(SchoolUserTypeCommissionType::class,'school_id','school_id')->where('user_type_id', $this->user_type_id);;
    }
}
