<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\v1\Claim;
use App\Models\v1\Redemption;
use App\Models\v1\Customer;
use App\Models\v1\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MerchantController extends Controller
{
    

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
            "merchant_phone_number" => "required|regex:/(0)[0-9]{9}/",
            "password" => "required"
        ]);

        // get user object
        $merchant = Merchant::where('merchant_phone_number', request()->merchant_phone_number)->first();
        // do the passwords match?

        if ($merchant == null || !Hash::check($request->password, $merchant->password)) {
            // no they don't
            return response(["status" => "fail", "message" => "Invalid Credentials"]);
        }

        if ($merchant->merchant_flagged) {
            return response(["status" => "fail", "message" => "Account access restricted"]);
        }

        $tokenResult = $merchant->createToken("authToken", [$merchant->merchant_scope]);

        return response([
            "status" => "success",
            "merchant_name" => $merchant->merchant_name,
            "access_token" => $tokenResult->accessToken
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
public function get_dashboard(Request $request)
{

    if (!Auth::guard('merchant')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->merchant_flagged) {
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    $where_array = array(
        ['vendor_paid_fiat', '=',  0],
        ['merchant_id', '=',  auth()->user()->merchant_id],
    ); 

    $unpaid_redemptions = DB::table('redemptions')
    ->selectRaw('count(*)')
    ->where($where_array)
    ->get();

    $unpaid_redemptions = (array) $unpaid_redemptions[0];
    $unpaid_redemptions = (array) $unpaid_redemptions["count(*)"];

    /*
    $cedis_sum_unpaid_redemptions = DB::table('redemptions')
    ->where($where_array)
    ->sum('redemptions.claim_amount');
    */



    $points_to_one_cedi = DB::table('settings')
    ->where("settings_id", "=", "points_to_one_cedi")
    ->first();

    if($points_to_one_cedi != null){
        $points_to_one_cedi = $points_to_one_cedi->settings_info_1;
    } else {
        $points_to_one_cedi = "N/A";
    }
    


    $redemptions = DB::table('redemptions')
    ->select('redemptions.*')
    ->where($where_array)
    ->get();


    return response([
        "status" => "success", 
        "message" => "Operation successful", 
        "unpaid" => $unpaid_redemptions[0], 
        "points_to_one_cedi" => $points_to_one_cedi, 
        "merchant_balance" => auth()->user()->merchant_balance, 
        "merchant_vcode" => auth()->user()->merchant_vcode_link, 
        "redemptions" => $redemptions
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
public function get_redemptions(Request $request)
{

    if (!Auth::guard('merchant')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->merchant_flagged) {
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    $login_data = $request->validate([
        "customer_phone" => "string|max:20",
    ]);


    if(isset($request->customer_phone)){
        $where_array = array(
            ['customer_phone', '=',  $request->customer_phone],
        ); 
    } else {
        $where_array = array(
            ['merchant_id', '=',  auth()->user()->merchant_id],
        ); 
    }



    $redemptions = DB::table('redemptions')
    ->select('redemptions.*')
    ->where($where_array)
    ->get();


    return response([
        "status" => "success", 
        "message" => "Operation successful", 
        "redemptions" => $redemptions
        ]);
    }


public function update_redemption(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('merchant')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->merchant_flagged) {
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }


    $validatedData = $request->validate([
        "redemption_id" => "bail|required|integer",
        "vendor_paid_fiat" => "bail|required|integer|max:2"
    ]);

    

    $where_array = array(
        ['redemptions.redemption_id', '=', intval($request->redemption_id)],
        ['redemptions.merchant_id', '=', auth()->user()->merchant_id]
    ); 

    $redemption = Redemption::where($where_array)->first();

    if ($redemption != null && $redemption->merchant_id == auth()->user()->merchant_id && $redemption->vendor_paid_fiat == 0) {
    
        $redemption = Redemption::find($request->redemption_id);
        $redemption->vendor_paid_fiat = intval($request->vendor_paid_fiat); 
        $redemption->save();

        if($request->vendor_paid_fiat == 2){

            $where_array = array(
                ['customers.customer_id', '=', $redemption->customer_id]
            ); 
            $customer = Customer::where($where_array)->first();
            $customer->points = $customer->points + $redemption->redeemed_points;
            $customer->save();
            return response([
                "status" => "success", 
                "message" => "Redemption declined successfully", 
                "redemption_id" => $request->redemption_id, 
                "vendor_paid_fiat" => 2
                ]);
        } else if($request->vendor_paid_fiat == 1){

            $where_array = array(
                ['merchants.merchant_id', '=', $redemption->merchant_id]
            ); 
            $merchant = Merchant::where($where_array)->first();
            $merchant->merchant_balance = $merchant->merchant_balance + $redemption->redemption_cedi_equivalent_paid;
            $merchant->save();
            return response([
                "status" => "success", 
                "message" => "Redemption completed successfully", 
                "redemption_id" => $request->redemption_id, 
                "vendor_paid_fiat" => 1
                ]);
        } else {
            return response(["status" => "success", "message" => "Something went awry"]);
        }
    } else if($redemption->vendor_paid_fiat == 1) {
        return response(["status" => "fail", "message" => "Redemption has already been completed"]);
    }  else if($redemption->vendor_paid_fiat == 2) {
        return response(["status" => "fail", "message" => "Redemption has already been declined"]);
    }   else {
        return response(["status" => "fail", "message" => "Redemption not found"]);
    }


}


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| THIS FUNCTION GETS SEARCHES FOR A LIST OF BUREAUS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
public function get_claims(Request $request)
{

    if (!Auth::guard('merchant')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->merchant_flagged) {
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }

    $where_array = array(
        ['merchant_id', '=',  auth()->user()->merchant_id],
        ['paid_status', '=',  0],
    ); 

    $unpaid_redemptions = DB::table('claims')
    ->selectRaw('count(*)')
    ->where($where_array)
    ->get();

    $unpaid_redemptions = (array) $unpaid_redemptions[0];
    $unpaid_redemptions = (array) $unpaid_redemptions["count(*)"];


    $where_array = array(
        ['merchant_id', '=',  auth()->user()->merchant_id],
    ); 

    $claims = DB::table('claims')
    ->select('claims.*')
    ->where($where_array)
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
        "unpaid" => $unpaid_redemptions[0], 
        "merchant_balance" => auth()->user()->merchant_balance, 
        "claims" => $claims
        ]);
    }


public function make_claim(Request $request)
{
    $log_controller = new LogController();

    if (!Auth::guard('merchant')->check()) {
        return response(["status" => "fail", "message" => "Permission Denied. Please log out and login again"]);
    }

    if (auth()->user()->merchant_flagged) {
        $request->user()->token()->revoke();
        return response(["status" => "fail", "message" => "Account access restricted"]);
    }


    $validatedData = $request->validate([
        "claim_amount" => "bail|required|numeric",
    ]);

    
    $where_array = array(
        ['merchant_id', '=', auth()->user()->merchant_id]
    ); 

    $merchant = Merchant::where($where_array)->first();

    if ($merchant != null && $merchant->merchant_id == auth()->user()->merchant_id && $merchant->merchant_balance >= $request->claim_amount) {
    
        $merchant->merchant_balance = $merchant->merchant_balance - $request->claim_amount;
        $merchant->save();

        $claim = new Claim;
        $claim->claim_amount = $request->claim_amount; 
        $claim->paid_status = 0; 
        $claim->claim_flagged = 0; 
        $claim->merchant_id = auth()->user()->merchant_id; 
        $claim->save();

        return response([
            "status" => "success", 
            "message" => "Claim made successfully", 
            "new_balance" => $merchant->merchant_balance, 
            "message" => "Claim made successfully"
            ]);

    } else if( $merchant->merchant_balance < $request->claim_amount) {
        return response(["status" => "fail", "message" => "Balance is insufficient to make claim"]);
    } else {
        return response(["status" => "fail", "message" => "Loyalty Account not found"]);
    }


}





}
