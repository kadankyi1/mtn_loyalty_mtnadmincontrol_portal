<?php
namespace App\Http\Controllers\Api\v1;

use App\Models\v1\Merchant;
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
        "admin_view_claims" => "bail|nullable|regex:(admin_view_claims)",
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
        $validatedData["creator_admin_id"] = auth()->user()->admin_id;
    
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
        if(trim($request->admin_view_claims) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_scope"]  .  " " .  $request->admin_view_claims;
        }
    
    
        Administrator::create($validatedData);
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




    $admins = DB::table('administrators')
    ->select('administrators.*')
    ->get();

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
        "admin_view_claims" => "bail|nullable|regex:(admin_view_claims)",
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
        if(trim($request->admin_view_claims) != ""){
            $validatedData["admin_scope"] = $validatedData["admin_view_claims"]  .  " " .  $request->admin_view_claims;
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



public function add_merchant(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (!$request->user()->tokenCan('admin_add_merchant')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchant|Admin", "Permission denined for trying to add merchant");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchant|Admin", "Adding merchant failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    if (!Hash::check($request->admin_pin, auth()->user()->admin_pin)) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchant|Admin", "Adding merchant failed because of incorrect pin");
        return response(["status" => "fail", "message" => "Incorrect pin."]);
    }

    $validatedData = $request->validate([
        "merchant_name" => "bail|required|max:55",
        "merchant_phone_number" => "bail|required|regex:/(0)[0-9]{9}/|min:10|max:10",
        "merchant_email" => "bail|required|email|max:100",
        "merchant_location" => "bail|required",
        "admin_pin" => "bail|required|min:4|max:8",
    ]);

    $validatedData["merchant_scope"] = "merchant_view_redemptions merchant_accept_redemptions";


    $merchant = Merchant::where('merchant_phone_number', $request->merchant_phone_number)->first();

    if ($merchant != null && $merchant->merchant_phone_number == $request->merchant_phone_number) {
        return response(["status" => "fail", "message" => "The phone number is registered to another merchant."]);
    } else {

        $create_vcode_user_client = new \GuzzleHttp\Client();

        $response = $create_vcode_user_client->request(
            'POST', 
            'http://vstgh3.stakcloud.com/api/external/customer', 
            [
                'headers' => [
                    'apiUser' => 'Loyalty', 
                    'apiKey' => 'Loyalty123!',
                ],
                'form_params' => [
                    'name' => $request->merchant_name, 
                    'phone' => $request->merchant_phone_number, 
                    'email' => $request->merchant_email, 
                    'address' => $request->merchant_location, 
                ]   
        ]);


        $statusCode = $response->getStatusCode();
        $contents = $response->getBody()->getContents();
        $contents = json_decode($contents,true);

        if($statusCode == 200 && isset($contents['data']['id']) && $contents['data']['id'] > 0){
            $vcode_user_id = $contents['data']['id'];
        } else {
            return response(["status" => "fail", "message" => "VCode user creation error. Failed to create merchant"]);
        }

        $description = "Mtn Merchant " . $request->merchant_phone_number;
        
        $create_vcode_for_vcode_user_client = new \GuzzleHttp\Client();

        $response = $create_vcode_for_vcode_user_client->request(
            'POST', 
            'http://vstgh3.stakcloud.com/api/external/vcode', 
            [
                'headers' => [
                    'apiUser' => 'Loyalty', 
                    'apiKey' => 'Loyalty123!',
                ],
                'form_params' => [
                    'description' => $description, 
                    'quantity' => 1, 
                    'customer_id' => $vcode_user_id, 
                ]   
        ]);


        $statusCode = $response->getStatusCode();
        $contents = $response->getBody()->getContents();
        $contents = json_decode($contents,true);

        if($statusCode == 200 && isset($contents['data']['message']) && $contents['data']['message'] == "Vcode created"){
            $vcode_user_vcode = $contents['data']['vcodes'][0];
            $vcode_user_vcode_link = $contents['data']['links'][0];
        } else {
            return response(["status" => "fail", "message" => "VCode creation error. Failed to create merchant"]);
        }

        
        $validatedData["merchant_pin"] = Hash::make(substr($request->merchant_phone_number,-4));
        $validatedData["password"] = bcrypt($request->merchant_phone_number);
        $validatedData["admin_id"] = auth()->user()->admin_id;
    
        $merchant = new Merchant();
        $merchant->merchant_name = $validatedData["merchant_name"]; 
        $merchant->merchant_location = $validatedData["merchant_location"];
        $merchant->merchant_scope = $validatedData["merchant_scope"];
        $merchant->merchant_phone_number = $validatedData["merchant_phone_number"];
        $merchant->merchant_email = $validatedData["merchant_email"];
        $merchant->merchant_vcode_user_id = $vcode_user_id;
        $merchant->merchant_vcode = $vcode_user_vcode;
        $merchant->merchant_vcode_link = $vcode_user_vcode_link;
        $merchant->merchant_pin = $validatedData["merchant_pin"];
        $merchant->password = $validatedData["password"];
        $merchant->merchant_flagged = false;
        $merchant->admin_id = $validatedData["admin_id"];
        $merchant->save();

        return response(["status" => "success", "message" => "Merchant added successsfully."]);
    }


}



public function edit_merchant(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (!$request->user()->tokenCan('admin_update_merchant')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchants|Admin", "Permission denied for trying to update merchant");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchants|Admin", "Updating merchant failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    if (!Hash::check($request->admin_pin, auth()->user()->admin_pin)) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchants|Admin", "Updating merchant failed because of incorrect pin");
        return response(["status" => "fail", "message" => "Incorrect pin."]);
    }


    $validatedData = $request->validate([
        "merchant_id" => "bail|required|integer",
        "merchant_name" => "bail|required|max:55",
        "merchant_email" => "bail|email|max:100",
        "merchant_location" => "bail|required",
        "merchant_flagged" => "bail|required|integer",
        "admin_pin" => "bail|required|min:4|max:8",
    ]);

    

    $where_array = array(
        ['merchants.merchant_id', '=', $request->merchant_id]
    ); 


    $merchant = Merchant::where($where_array)->first();

    if ($merchant != null && $merchant->merchant_id == $request->merchant_id) {
    
        $merchant = Merchant::find($request->merchant_id);
        $merchant->merchant_name = $request->merchant_name; 
        $merchant->merchant_email = $request->merchant_email; 
        $merchant->merchant_location = $request->merchant_location; 
        $merchant->merchant_flagged = $request->merchant_flagged;
        $merchant->save();
        return response(["status" => "success", "message" => "Merchant updated successsfully."]);
    } else {
        return response(["status" => "fail", "message" => "Merchant not found"]);
    }


}


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| THIS FUNCTION GETS ONE ADMIN
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
public function search_one_merchant(Request $request)
{

    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (!$request->user()->tokenCan('admin_view_merchant')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchants|Admin", "Permission denined for trying to view one merchant");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    $request->validate([
        "merchant_phone_number" => "bail|required|max:15",
    ]);

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Merchants|Admin", "Getting one merchant failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }



    $this_merchant = DB::table('merchants')
    ->where("merchant_phone_number", "=", $request->merchant_phone_number)
    ->get();

    $unpaid_claims = 0;
    $admin_fullname = "Unavailable";

    if(!isset($this_merchant[0])){
        $where_array = array(
            ['administrators.admin_id', '=', $this_merchant[0]->admin_id]
        ); 

        $admin = Administrator::where($where_array)->first();


        if ($admin != null && $admin->admin_firstname != "" && $admin->admin_surname != "") {
            $admin_fullname = $admin->admin_firstname . " " . $admin->admin_surname;
        }
    
        $where_array = array(
            ['paid_status', '=',  0],
            ['merchant_id', '=',  $this_merchant[0]->merchant_id],
            ['claim_flagged', '=',  0],
        );     

        $unpaid_claims = DB::table('claims')
        ->selectRaw('count(*)')
        ->where($where_array)
        ->get();    

        if(!isset($unpaid_claims[0])){
            $unpaid_claims = (array) $unpaid_claims[0];
            $this_merchant[0]->merchant_unpaid_claims = $unpaid_claims["count(*)"];
        } else {
            $unpaid_claims = 0;
            $this_merchant[0]->merchant_unpaid_claims = $unpaid_claims;
        }
    
    }

    $this_merchant[0]->admin_fullname = $admin_fullname;
    $this_merchant[0]->merchant_unpaid_claims = $unpaid_claims;
    
    return response([
        "status" => "success", 
        "message" => "Operation successful", 
        "data" => $this_merchant
        ]);
        
}


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| THIS FUNCTION GETS SEARCHES FOR A LIST OF BUREAUS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
public function get_merchant_claims(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }
    
    if (!$request->user()->tokenCan('admin_view_claims')) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Claims|Admin", "Permission denined for trying to get merchants claims");
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Claims|Admin", "Getting merchants claims failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    $request->validate([
        "merchant_id" => "bail|required",
    ]);

    $where_array = array(
        ['merchant_id', '=',  $request->merchant_id],
        ['vendor_paid_fiat', '=',  1],
    ); 

    $unpaid_claims = DB::table('claims')
    ->selectRaw('count(*)')
    ->where($where_array)
    ->get();

    $where_array = array(
        ['merchant_id', '=',  $request->merchant_id]
    ); 


    $claims = DB::table('claims')
    ->select('claims.*')
    ->where($where_array)
    ->simplePaginate(50);

    
    return response(["status" => "success", "message" => "Operation successful", "data" => $claims, "unpaid" => $unpaid_claims]);
}



/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| THIS FUNCTION GETS SEARCHES FOR A LIST OF BUREAUS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
public function get_dashboard(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('api')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->admin_flagged) {
        $log_controller->save_log("administrator", auth()->user()->admin_id, "Dashboard|Admin", "Getting dashboard failed because admin is flagged");
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    $where_array = array(
        ['paid_status', '=',  0],
    ); 

    $unpaid_claims = DB::table('claims')
    ->selectRaw('count(*)')
    ->where($where_array)
    ->get();

    $unpaid_claims = (array) $unpaid_claims[0];
    $unpaid_claims = (array) $unpaid_claims["count(*)"];

    $cedis_sum_unpaid_claims = DB::table('claims')
    ->where($where_array)
    ->sum('claims.claim_amount');


    $al_merchants = DB::table('merchants')
    ->selectRaw('count(*)')
    ->get();

    $al_merchants = (array) $al_merchants[0];
    $al_merchants = (array) $al_merchants["count(*)"];


    $points_to_one_cedi = DB::table('settings')
    ->where("settings_id", "=", "points_to_one_cedi")
    ->first();

    if($points_to_one_cedi != null){
        $points_to_one_cedi = $points_to_one_cedi->settings_info_1;
    } else {
        $points_to_one_cedi = "N/A";
    }
    


    $claims = DB::table('claims')
    ->select('claims.*')
    ->where("paid_status", "=", 0)
    ->get();

    for ($i=0; $i < count($claims); $i++) { 

        if($claims[$i]->merchant_id > 0 && $claims[$i]->merchant_id != null){
            $this_merchant = DB::table('merchants')
            ->where("merchant_id", "=", $claims[$i]->merchant_id)
            ->get();
        
            if(isset($this_merchant[0])){
                $claims[$i]->merchant_fullname = $this_merchant[0]->merchant_name;
                $claims[$i]->merchant_phone_number = $this_merchant[0]->merchant_phone_number;
            } else {
                $claims[$i]->merchant_fullname = "N/A";
                $claims[$i]->merchant_phone_number = "N/A";
            }
        } else {
            $claims[$i]->merchant_fullname = "N/A";
            $claims[$i]->merchant_phone_number = "N/A";
        }

        if($claims[$i]->payer_admin_id > 0 && $claims[$i]->payer_admin_id != null){
            $this_admin = DB::table('administrators')
            ->where("admin_id", "=", $claims[$i]->payer_admin_id)
            ->get();
        
            if(isset($this_admin[0])){
                $claims[$i]->admin_fullname = $this_admin[0]->admin_firstname . " " . $this_admin[0]->admin_surname;
            } else {
                $claims[$i]->admin_fullname = "N/A";
            }
        } else {
            $claims[$i]->admin_fullname = "N/A";
        }
    }

    return response([
        "status" => "success", 
        "message" => "Operation successful", 
        "merchants" => $al_merchants[0], 
        "unpaid" => $unpaid_claims[0], 
        "sum_unpaid" => $cedis_sum_unpaid_claims, 
        "points_to_one_cedi" => $points_to_one_cedi, 
        "claims" => $claims
        ]);
}





}
