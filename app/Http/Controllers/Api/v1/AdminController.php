<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Models\Administrator;

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
            "admin_scope" => "bail|required"
        ]);

        $validatedData["admin_pin"] = Hash::make($request->admin_pin);
        $validatedData["password"] = bcrypt($request->password);
        $validatedData["admin_flagged"] = false;

        $administrator = Administrator::create($validatedData);

        $accessToken = $administrator->createToken("authToken", [$validatedData["admin_scope"]])->accessToken;

        return response(["administrator" => $administrator, "access_token" => $accessToken]);
    }

}
