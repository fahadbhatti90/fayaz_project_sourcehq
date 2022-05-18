<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\InvitationsController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PositionController;
use App\Http\Controllers\API\HireTypeController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\TaxLabelController;
use App\Http\Controllers\API\jobsController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('client-signup',[ClientController::class,'clientSignUp']); // Singup URL
Route::post('client-login',[ClientController::class,'clientLogin']); // Singup URL
Route::post('reset_password_without_token',[ClientController::class,'validatePasswordRequest']);
Route::post('reset_password_with_token',[ClientController::class,'resetPassword']);
Route::post('showRegistrationForm',[InvitationsController::class,'showRegistrationForm']);
Route::post('AddUsers',[ClientController::class,'addUsers']); // Singup URL
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('submit_business',[BusinessController::class,'submit_business']);
    Route::get('ManageAccountBusiness',[BusinessController::class,'ManageAccountBusiness']);
    Route::post('UpdateAccountBusiness',[BusinessController::class,'UpdateAccountBusiness']);
    Route::get('SetUpPrimaryData',[BusinessController::class,'SetUpPrimaryData']);
    Route::get('ShowLanguageCurrency',[BusinessController::class,'ShowLanguageCurrency']);
    Route::post('UpdateLanguageCurrency',[BusinessController::class,'UpdateLanguageCurrency']);
    Route::get('GetAllUserRoles',[RoleController::class,'GetAllUserRoles']);
    Route::get('GetRolesClients/{id}',[RoleController::class,'GetRolesClients']);
    //Route::post('invitations', 'InvitationsController@store')->name('storeInvitation');
    Route::post('storeInvitation',[InvitationsController::class,'storeInvitation']);
    Route::post('ChangeClientStatus',[ClientController::class,'ChangeClientStatus']);
    //locations
    Route::post('AddLocation',[LocationController::class,'AddLocation']);
    Route::get('GetLocationsData',[LocationController::class,'GetLocationsData']);
    Route::get('ShowAllLocations',[LocationController::class,'ShowAllLocations']);
    Route::get('ShowAllJobCategories',[CategoryController::class,'ShowAllJobCategories']);
    Route::post('AddCategories',[CategoryController::class,'AddCategories']);
    Route::get('ShowAllJobPositions',[PositionController::class,'ShowAllJobPositions']);
    Route::post('AddPosition',[PositionController::class,'AddPosition']);
    Route::get('ShowAllHireType',[HireTypeController::class,'ShowAllHireType']);
    Route::post('AddHireType',[HireTypeController::class,'AddHireType']);
    Route::post('AddDepartment',[DepartmentController::class,'AddDepartment']);
    Route::get('ShowAllDepartments',[DepartmentController::class,'ShowAllDepartments']);
    Route::post('AddTaxLabel',[TaxLabelController::class,'AddTaxLabel']);
    Route::get('ShowAllTaxLabels',[TaxLabelController::class,'ShowAllTaxLabels']);
    Route::get('ShowAddJobForm/{id}',[jobsController::class,'ShowAddJobForm']);
    Route::post('AddJobDetails',[jobsController::class,'AddJobDetails']);
    Route::get('ListAllJobs/{id}',[jobsController::class,'ListAllJobs']);
    Route::get('GetJobDetail/{id}',[jobsController::class,'GetJobDetail']);
    Route::post('EditJobDetails',[jobsController::class,'EditJobDetails']);
    Route::post('changeJobStatus',[jobsController::class,'changeJobStatus']);
    Route::post('deleteJob',[jobsController::class,'deleteJob']);
    Route::post('archiveJob',[jobsController::class,'archiveJob']);
});



Route::post('user-signup',[UserController::class,'userSignUp']); // Singup URL
Route::post('user-login',[UserController::class,'userLogin']); // lOGIN url
Route::post('verifyConfirmationCode',[ClientController::class,'verifyConfirmationCode']);
Route::post('resendConfirmationCode',[ClientController::class,'resendConfirmationCode']);
Route::post('get_business_groups',[BusinessController::class,'get_business_groups']);



Route::middleware('auth:api')->group(function () {

Route::get("get-user", [UserController::class,'userInfo']);
// roles and permission
Route::resource('roles', RoleController::class);
Route::get("user-permissions/{id}", [RoleController::class,'user_role_permission']);
});
