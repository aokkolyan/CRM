<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Sidemenu;
use App\Models\UserType;
use App\Models\Permission;
use App\Imports\UserImport;
use App\Models\BranchConfig;
use Illuminate\Http\Request;
use App\Policies\PolicyHelper;
use App\Http\Resources\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ErrorBadRequest;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\ErrorUnauthorized;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepositoryInterface;
use App\Http\Resources\RoleResourceCollection;
use App\Http\Resources\UserResourceCollection;
use Exception;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class UserController extends Controller
{
    /**
     * func login
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }
    public function login(Request $request) {
      
        $credentials = $request->validate([
            'user_name' => ['required'],
            'password' => ['required'],
        ]);  
        // $credentials = [
        //     'phone' => $request->phone,
        //     'password' => $request->password,
        // ];
        // if (Auth::attempt($credentials)) {
        //     // Authentication passed...
        //     return back();
        // }



        $userAgent = $request->header('User-Agent');
      
        // and obtain more detailed device information.
        $agent = new Agent();
        $agent->setUserAgent($userAgent);
         // Determine if it's a mobile device
        $isMobile = $agent->isMobile();

        // Get the device type (e.g., mobile, tablet, desktop)
        $deviceType = $agent->device();

        // Get the browser name and version
        $browserName = $agent->browser();
        $browserVersion = $agent->version($browserName);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            $user=Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            $menu=[];
            $nonReportModuleId=[0];
            $reportModuleId=[0];
            $roles=$user->roles;
            $isAdmin=0;
          
            //
            foreach($roles as $role){
                if($role->id== 1){$isAdmin++;}
               
            }
            if($isAdmin>0){
                $menu=Sidemenu::get();
                $data = [
                    'device-info'=>[
                        'userAgent' => $userAgent,
                        'isMobile' => $isMobile,
                        'deviceType' => $deviceType,
                        'browserName' => $browserName,
                        'browserVersion' => $browserVersion,
                        'agent'=>$agent,
                    ],
                    'user' => new UserResource($user),
                    'menu'=>$menu,
                    'token' => $token
                ];
                return new Response($data);
            }else{
                $permission=Permission::get()
                    ->whereIn('permission',['viewAny','report'])->whereIn('role_id',$roles->pluck('id'));
                foreach($permission as $p){
                    if($p->permission=='viewAny'){
                        array_push($nonReportModuleId,$p->module_id);
                    }else{
                        array_push($reportModuleId,$p->module_id);
                    }
                }
                
                $menu=Sidemenu::
                where(function($query) use($nonReportModuleId){
                    return $query->whereIn('module_id',$nonReportModuleId)
                        ->where('is_report',0);
                })
                ->orWhere(function($query) use($reportModuleId){
                    return $query->whereIn('module_id',$reportModuleId)
                        ->where('is_report',1);
                })->get();
            }
            $data = [
                'user' => new UserResource($user),
                'menu'=>$menu,
                'token' => $token
            ];

            //
            return new Response($data);
        }
 
        return [
            'status'=>403,
            'message'=>'Wrong Credential'];
     
    }
    /**
     * register user
    */

   
    
    public function store(Request $request) {
      
        $validated=$request->validate([
            'name' => ['required', 'max:25'],
            'phone' => ['required', 'max:10'],
            'email' => ['required', 'max:50','email'],
            'user_name' => ['required', 'max:50','unique:users,user_name'],
            'role_id'=> ['required'],
            'password'=>['required']
        ]);
        
        //create user`
        $user=User::create([
            'name' => $request->name,
            'phone'=> $request->phone,
            'email' => $request->email,
            'user_name'=>$request->user_name,
            'branch_id' => $request->branch_id,
            'user_type_id'=> $request->user_type_id,
            'password' => Hash::make($request->password),
        ]);
        foreach($request->role_id as $role){
          
            RoleUser::create([
                'user_id'=>$user->id,
                'role_id'=>$role['id'],
            ]);
        }
        
        
        //redirect back to Users
        return [
            'message'=> 'Register has been added successfully. ',
            'snackColor'=> 'success',
            "status" => ApiResponse::STATUS_OK,
        ];
        
    }

    public function getUsertype(Request $request) {
        // $usertypes = DB::table('user_types')->get();
        $usertypes = DB::table('user_types')->whereNotIn('name_en',['Employee'])->get();
        // dd($usertypes);
        return  [
            'usertypes'=> $usertypes,
        ];
    }

    public function register(Request $request) {
   
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'max:25'],
                'phone' => ['required', 'max:10','unique:users,phone'],
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
             $user_type = UserType::find($request->user_type_id);
            if($request->user_type_id == '2') {
                $user = User::create([
                    'name'         => $request->name,
                    'user_name'    => $request->phone,
                    'email'        => 'n/a'.$request->phone.'@mail.com',
                    'phone'        => $request->phone,
                    'user_type_id' => $request->user_type_id,
                    'student_id'   => $request->student_id,
                    'default_password'=>0,
                    'password'     => Hash::make($request->password),
                ]);
            }else if($request->user_type_id == '3') {
                $user = User::create([
                    'name'         => $request->name,
                    'user_name'    => $request->student_id,
                    'email'        => 'n/a'.$request->phone.'@mail.com',
                    'phone'        => $request->phone,
                    'user_type_id' => $request->user_type_id,
                    'student_id'   => $request->student_id,
                    'default_password'=>0,
                    'password'     => Hash::make($request->password),
                ]);
            }else if($request->user_type_id == '4') {
                $user = User::create([
                    'name'         => $request->name,
                    'user_name'    => $request->phone,
                    'email'        => 'n/a'.$request->phone.'@mail.com',
                    'phone'        => $request->phone,
                    'user_type_id' => $request->user_type_id,
                    'student_id'   => $request->student_id,
                    'default_password'=>0,
                    'password'     => Hash::make($request->password),
                ]);
            }
            Helper::setUserRoleByUserType($user->id, $user_type->name_en);
            DB::commit();
            return [
                'user_name'=>$user->user_name,
                'message'=> ' register successfully. ',
                'snackColor'=> 'success',
                "status" => ApiResponse::STATUS_OK,
            ];
        } catch (Exception $ex) {
            Log::info($ex);
            DB::rollback();
            return [
                'message'=> $ex,
                'snackColor'=> 'error',
                "status" => ApiResponse::STATUS_INTERNAL_ERROR_SERVER,
            ];
        }

    }
    /**
    *Logout
    */
    public function logout()
    { 
      
    }
    /**
     * Get all user
     */
    public function index() {
        //super admin
        if(PolicyHelper::isSuperAdmin(Auth::user()))return new UserResourceCollection(User::all());
        //referral admin
        else return new UserResourceCollection(User::where('id','>',13)->get());
    }

    /**
     * edit user
    */
    public function update(User $user,Request $request) {
        //get users 
        $validated=$request->validate([
            'name' => ['required', 'max:50'],
            'phone' => ['required', 'max:10'],
            'email' => ['max:50'],
            'user_name' => ['required', 'max:50','unique:users,user_name,'.$user->id],
           
        ]);
        //create user`
        $user->update($request->all());
        //redirect back to Users
        return [
            'message'=> 'User has been successfully updated. ',
            'snackColor'=> 'success',
            "status" => ApiResponse::STATUS_OK,
        ];
    }
    public function getUserRoles($id){
        return User::find($id)->roles;
    }
    public function addRole(Request $request){
        $validated=$request->validate([
            'user' => ['required'],
            'role' => ['required'],
        ]);

        $roleUser=RoleUser::where('user_id',$request->user)
                            ->where('role_id',$request->role)->get();
        if(count($roleUser)==0){
            RoleUser::create([
                'user_id'=>$request->user,
                'role_id'=>$request->role,
            ]);
            return [
                'message'=> 'Role has been added to this user. ',
                'snackColor'=> 'success',
                "status" => ApiResponse::STATUS_OK,
                'roles'=> User::find($request->user)->roles,
            ];
        }else{
            return [
                'message'=> 'User already has this role. ',
                'snackColor'=> 'danger',
                "status" => ApiResponse::STATUS_BAD_REQUEST,
                'roles'=> User::find($request->user)->roles,
            ];
        }
        
    }
    public function removeRole($id){
        $roleUser= RoleUser::find($id);
        $user_id=$roleUser->user_id;
        $roleUser->delete();
        return [
            'message'=> 'Role has been removed from this user. ',
            'snackColor'=> 'red',
            "status" => ApiResponse::STATUS_OK,
            'roles'=> User::find($user_id)->roles,
        ];
    }
    public function resetPassword(Request $request){
        
         $validated=$request->validate([
             'password' => ['required', 'max:25'],
         ]);
         //create user`
         $user=$request->user_id?User::find($request->user_id):Auth::user();
         $user->update([
             'password' => Hash::make($request->password),
             'default_password'=>false
         ]);
         //redirect back to Users
         return [
             'message'=> 'Password has been updated.',
             'snackColor'=> 'success',
             "status" => ApiResponse::STATUS_OK,
         ];
    }
    public function import(Request $request)
    {
        $file=$request->file('file');
        // $file=$request->file('file')->store('topics','public');
        // $image =Request::file('avatar')->store('topics','public');
        Excel::import(new UserImport,$file);
        return  [
            'message'=> 'Users has been imported. ',
            'snackColor'=> 'green',
        ];
    }
    public function addBranch(Request $request){
        $validated=$request->validate([
            'user' => ['required'],
            'branch' => ['required'],
        ]);

        $branchConfig=BranchConfig::where('user_id',$request->user)
                            ->where('branch_id',$request->branch)->get();
        if(count($branchConfig)==0){
            BranchConfig::create([
                'user_id'=>$request->user,
                'branch_id'=>$request->branch,
            ]);
            return [
                'message'=> 'Branch has been added to this user. ',
                'snackColor'=> 'success',
                "status" => ApiResponse::STATUS_OK,
                'branches'=> User::find($request->user)->branches,
            ];
        }else{
            return [
                'message'=> 'User already has this role. ',
                'snackColor'=> 'danger',
                'branches'=> User::find($request->user)->branches,
            ];
        }
        
    }
    public function removeBranch($id){
        $branchConfig= BranchConfig::find($id);
        $user_id=$branchConfig->user_id;
        $branchConfig->delete();
        return [
            'message'=> 'Branch has been removed from this user. ',
            'snackColor'=> 'red',
            "status" => ApiResponse::STATUS_OK,
            'branches'=> User::find($user_id)->branches,
        ];
    }
    public function profile(){
        return Auth::user();
    }
    public function changePassowrd(Request $request){
        $validated=$request->validate([
            'current' => ['required', 'max:30'],
            'new' => ['required', 'max:30'],
        ]);
        $user=Auth::user();
        if (Hash::check($request->current, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new),
            ]);
            return [
                'message'=> 'Change Password Successfully ',
                'snackColor'=> 'success',
                "status" => ApiResponse::STATUS_OK,
            ];
        }else{
            return [
                'message'=> 'Wrong Current Password',
                'snackColor'=> 'danger',
            ];
        }
    }
}
