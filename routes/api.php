<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SidemenuController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\ReferralStatusController;
use App\Http\Controllers\MyReferralController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParentReferralController;
use App\Http\Controllers\WeeklyReportReferralController;
use App\Http\Controllers\CommissionPaymentController;
use App\Http\Controllers\CompetitorProgramController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\MapLocationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserTypeController;
use App\Models\CompetitorProgram;
use App\Models\WeeklyReportReferral;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::group(['namespace' => 'Api'], function () {
//     require __DIR__ . '/api/user.php';
// });
Route::post('/user/login', [UserController::class, 'login']);
Route::get('register', [UserController::class,'getUsertype']);
Route::post('register', [UserController::class,'register']);
// Route::get('/userType',[UserTypeController::class,'userType']);

Route::group(['middleware' => ['auth.jwt']], function () {
    Route::post('user/logout',[UserController::class, 'logout']);
});

Route::group(['middleware' => ['auth.jwt','permission:DASHBOARD']], function () {
    Route::get('user/home', [HomeController::class, 'userDashboard']);
});

// Route::group(['middleware' => ['auth.jwt', 'permission:Branch']], function () {
    // Route::get('')
// });
#region Permission
Route::post('/permissions/update/{Role_id}', [PermissionController::class, 'update'])->name('permissions/update')->can('update','App\Models\Permission');;

#endregion Permission
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('user/addRole', [UserController::class, 'addRole']);
    Route::get('user/removeRole/{roleUser_id}', [UserController::class, 'removeRole']);
    Route::post('user/resetPassword', [UserController::class, 'resetPassword']);
    Route::get('user/{user_id}/roles', [UserController::class, 'getUserRoles']);
    Route::post('user/import', [UserController::class, 'import']);
    Route::post('referrals/{referral_id}/contact', [ReferralController::class, 'contact']);
    Route::get('referrals/{parent_referral_id}/show', [ReferralController::class, 'showParent']);
    Route::post('user/addBranch', [UserController::class, 'addBranch']);
    Route::get('user/removeBranch/{branchUser_id}', [UserController::class, 'removeBranch']);
    Route::post('commissions/{commission_id}/import', [CommissionController::class, 'import']);
    Route::get('allReferrers/', [ReferralController::class, 'allReferrers']);
    Route::get('allReferrers/{user_id}', [ReferralController::class, 'allUserReferrals']);
    Route::get('dashboards', [DashboardController::class, 'index']);
    Route::get('notifications', [DashboardController::class, 'notifications']);
    Route::post('/notifications/noticed',[DashboardController::class,'visit']);
    Route::get('weeklyReportReferrals/getEnrolledStudents', [WeeklyReportReferralController::class, 'create']);
    Route::put('weeklyReportReferrals/{weekly_report_referral_id}/check', [WeeklyReportReferralController::class, 'check']);
    Route::put('weeklyReportReferrals/{weekly_report_referral_id}/approve', [WeeklyReportReferralController::class, 'approve']);
    Route::put('weeklyReportReferrals/{referral_id}/removeReferral', [WeeklyReportReferralController::class, 'removeReferral']);
    Route::post('/schools/{school_id}/commissionType',[SchoolController::class,'commissionConfig']);
    Route::post('/mapLocations/uploadIcon',[MapLocationController::class,'uploadIcon']);
    Route::get('eventSchedule', [EventScheduleController::class, 'lateReferrlAlert']);


    Route::get('weeklyReportReferrals/exportWeekly',[WeeklyReportReferralController::class, 'exportWeeklyreport']);
    Route::get('weeklyReportReferrals/exportRerral',[WeeklyReportReferralController::class, 'exportReferral']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile/changePassword', [UserController::class, 'changePassowrd']);
    Route::apiResources([
        'roles'=>RoleController::class,
        'users'=>UserController::class,
        'modules' => ModuleController::class,
        'sidemenus' => SidemenuController::class,
        'branches' => BranchController::class,
        'schools' => SchoolController::class,
        'statuses' => StatusController::class,
        'paymentStatuses' => PaymentStatusController::class,
        'referralStatuses' => ReferralStatusController::class,
        'stages' => StageController::class,
        'sources' => SourceController::class,
        'categories' => CategoryController::class,
        'products' => ProductController::class,
        'myReferrals' => MyReferralController::class,
        'referrals' => ReferralController::class,
        'commissions' => CommissionController::class,
        'parentReferrals' => ParentReferralController::class,
        'weeklyReportReferrals'=>WeeklyReportReferralController::class,
        'commissionPayments'=>CommissionPaymentController::class,
        'mapLocations'=>MapLocationController::class,
        'programs'=>ProgramController::class,
        'competitorPrograms'=>CompetitorProgramController::class,
    ]);

});



Route::group(['middleware' => ['auth:api','permission:PERMISSION']], function () {
    Route::get('permissions/{role_id}', [PermissionController::class, 'get']);
    Route::post('permissions/{role_id}', [PermissionController::class, 'update']);
});

