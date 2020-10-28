<?php
namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Models\v1\Administrator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'admin_phone_number';
    }

/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| THIS FUNCTION REGISTES AN ADMIN AND PROVIDES THEM WITH AN ACCESS TOKEN
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            "admin_surname" => "bail|required|max:55",
            "admin_firstname" => "bail|required|max:55",
            "admin_othernames" => "bail|max:55",
            "admin_phone_number" => "bail|required|regex:/(0)[0-9]{9}/|min:10|max:10",
            "admin_email" => "bail|email|required|max:100",
            "admin_pin" => "bail|required|confirmed|min:4|max:8",
            "password" => "bail|required|confirmed|min:8|max:30",
            "admin_scope" => "bail|required",
        ]);

        $validatedData["admin_pin"] = Hash::make($request->admin_pin);
        $validatedData["password"] = bcrypt($request->password);
        $validatedData["admin_flagged"] = false;
        $validatedData["creator_admin_id"] = 1;

        $administrator = Administrator::create($validatedData);

        $accessToken = $administrator->createToken("authToken", [$validatedData["admin_scope"]])->accessToken;

        return response(["administrator" => $administrator, "access_token" => $accessToken]);
    }



/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| THIS FUNCTION LOGS IN AN ADMIN 
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
    
public function login(Request $request)
{
    $log_controller = new LogController();

    $login_data = $request->validate([
        "admin_phone_number" => "required|regex:/(0)[0-9]{9}/",
        "password" => "required"
    ]);

    if (!auth()->attempt($login_data)) {
        $log_controller->save_log("administrator", $request->admin_phone_number, "Login Admin", "Login failed");
        return response(["status" => "fail", "message" => "Invalid Credentials"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", $request->admin_phone_number, "Login Admin", "Login failed because admin is flagged");
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    $accessToken = auth()->user()->createToken("authToken", [auth()->user()->admin_scope])->accessToken;

    $log_controller->save_log("administrator", $request->admin_phone_number, "Login Admin", "Login successful");

    return response([
        "status" => "success",
        "admin_firstname" => auth()->user()->admin_firstname,
        "admin_surname" => auth()->user()->admin_surname,
        "access_token" => $accessToken
    ]);
}



public function add_admin(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (!$request->user()->tokenCan('admin_add_admin')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Permission denined for trying to add administrator");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Adding administrator failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    if (!Hash::check($request->admin_pin, auth()->user()->admin_pin)) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Addming administrator failed because of incorrect pin");
        return response(["status" => "fail", "message" => "Incorrect pin."]);
    }

    $validatedData = $request->validate([
        "admin_surname" => "bail|required|max:55",
        "admin_firstname" => "bail|required|max:55",
        "admin_othernames" => "bail|max:55",
        "admin_phone_number" => "bail|required|regex:/(0)[0-9]{9}/|min:10|max:10",
        "admin_email" => "bail|email|required|max:100",
        "admin_add_admin" => "bail|nullable|regex:(admin_add_admin)",
        "admin_update_admin" => "bail|nullable|regex:(admin_update_admin)",
        "admin_view_admins" => "bail|nullable|regex:(admin_view_admins)",
        "admin_add_merchant" => "bail|nullable|regex:(admin_add_merchant)",
        "admin_update_merchant" => "bail|nullable|regex:(admin_update_merchant)",
        "admin_view_merchant" => "bail|nullable|regex:(admin_view_merchant)",
        "admin_view_redemptions" => "bail|nullable|regex:(admin_view_redemptions)",
        "admin_pin" => "bail|required|min:4|max:8",
    ]);



    $admin = Administrator::where('admin_phone_number', $request->admin_phone_number)->first();
    $admin2 = Administrator::where('admin_email', $request->admin_email)->first();

    if ($admin != null && $admin->admin_phone_number == $request->admin_phone_number) {
        return response(["status" => "fail", "message" => "The phone number is registered to another administrator."]);
    } else if ($admin2 != null && $admin2->admin_email == $request->admin_email) {
        return response(["status" => "fail", "message" => "The email address is registered to another administrator."]);
    } else {
        $validatedData["admin_pin"] = Hash::make(substr($request->admin_phone_number,-4));
        $validatedData["password"] = bcrypt($request->admin_phone_number);
        $validatedData["admin_flagged"] = false;
    
        $validatedData["admin_scope"] = "";

        if(trim($request->admin_add_admin) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"] . $request->admin_add_admin;
        }
        if(trim($request->admin_update_admin) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_update_admin;
        }
        if(trim($request->admin_view_admins) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_view_admins;
        }
        if(trim($request->admin_add_merchant) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_add_merchant;
        }
        if(trim($request->admin_update_merchant) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_update_merchant;
        }
        if(trim($request->admin_view_merchant) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_view_merchant;
        }
        if(trim($request->admin_view_redemptions) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_view_redemptions"]  .  " " .  $request->admin_view_redemptions;
        }
    
    
        $administrator = Administrator::create($validatedData);
        return response(["status" => "success", "message" => "Administrator added successsfully."]);
    }


}


public function get_all_admins(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }
    
    if (!$request->user()->tokenCan('admin_view_admins')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Permission denined for trying to view administrators");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Fetching administrators failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    $request->validate([
        "page" => "bail|required|integer",
    ]);



    $admins = DB::table('administrators')
    ->select('administrators.*')
    ->simplePaginate(50);

    for ($i=0; $i < count($admins); $i++) { 

        if($admins[$i]->creator_admin_id > 0 || $admins[$i]->creator_admin_id != null){
            $this_admin = DB::table('administrators')
            ->where("admin_id", "=", $admins[$i]->creator_admin_id)
            ->get();
        
            $admins[$i]->creator_name = $this_admin[0]->admin_firstname . " " . $this_admin[0]->admin_surname;
        } else {
            $admins[$i]->creator_name = "Shrinq(Manual Addition)";
        }
    }

    return response(["status" => "success", "message" => "Operation successful", "data" => $admins]);
}


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| THIS FUNCTION GETS ONE ADMIN
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
public function get_one_admin(Request $request)
{

    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (!$request->user()->tokenCan('admin_view_admins')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Permission denined for trying to view one admin");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    $request->validate([
        "admin_id" => "bail|required|integer",
    ]);

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Getting one admin failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }


    $this_admin = DB::table('administrators')
    ->where("admin_id", "=", $request->admin_id)
    ->get();
    


    return response(["status" => "success", "message" => "Operation successful", "data" => $this_admin]);
        
}


public function edit_admin(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (!$request->user()->tokenCan('admin_update_admin')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Permission denined for trying to update administrator");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Updating administrator failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    if (!Hash::check($request->admin_pin, auth()->user()->admin_pin)) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Administrators|Admin", "Addming administrator failed because of incorrect pin");
        return response(["status" => "fail", "message" => "Incorrect pin."]);
    }

    $validatedData = $request->validate([
        "admin_id" => "bail|required|integer",
        "admin_surname" => "bail|required|max:55",
        "admin_firstname" => "bail|required|max:55",
        "admin_othernames" => "bail|max:55",
        "admin_flagged" => "bail|required|integer",
        "admin_add_admin" => "bail|nullable|regex:(admin_add_admin)",
        "admin_update_admin" => "bail|nullable|regex:(admin_update_admin)",
        "admin_view_admins" => "bail|nullable|regex:(admin_view_admins)",
        "admin_add_merchant" => "bail|nullable|regex:(admin_add_merchant)",
        "admin_update_merchant" => "bail|nullable|regex:(admin_update_merchant)",
        "admin_view_merchant" => "bail|nullable|regex:(admin_view_merchant)",
        "admin_view_redemptions" => "bail|nullable|regex:(admin_view_redemptions)",
        "admin_pin" => "bail|required|min:4|max:8",
    ]);
    

    $where_array = array(
        ['administrators.admin_id', '=', $request->admin_id]
    ); 


    $admin = Administrator::where($where_array)->first();

    if ($admin != null && $admin->admin_id == $request->admin_id) {
    
        $validatedData["admin_scope"] = "";

        if(trim($request->admin_add_admin) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"] . $request->admin_add_admin;
        }
        if(trim($request->admin_update_admin) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_update_admin;
        }
        if(trim($request->admin_view_admins) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_view_admins;
        }
        if(trim($request->admin_add_merchant) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_add_merchant;
        }
        if(trim($request->admin_update_merchant) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_update_merchant;
        }
        if(trim($request->admin_view_merchant) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_view_merchant;
        }
        if(trim($request->admin_view_redemptions) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_view_redemptions"]  .  " " .  $request->admin_view_redemptions;
        }
    
        $admin = Administrator::find($request->admin_id);
        $admin->admin_surname = $request->admin_surname; 
        $admin->admin_firstname = $request->admin_firstname;
        $admin->admin_othernames = $request->admin_othernames;
        $admin->admin_scope = $validatedData["admin_scope"];
        $admin->admin_flagged = $request->admin_flagged;
        $admin->save();
        return response(["status" => "success", "message" => "Administrator updated successsfully."]);
    } else {
        return response(["status" => "fail", "message" => "Administrator not found"]);
    }


}




}
