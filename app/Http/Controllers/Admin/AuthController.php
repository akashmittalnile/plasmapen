<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Dev Name :- Dishant Gupta
    public function login()
    {
        try {
            return view('auth.login');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev Name :- Dishant Gupta
    public function authenticateAdmin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $user = User::where("email", $request->email)->first();
                if (isset($user->id)) {
                    if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                        return response()->json(['status' => true, 'message' => 'Successfully loggedin.', 'route' => route("admin.dashboard")]);
                    } else {
                        return errorMsg('Invalid Email or Password');
                    }
                } else {
                    return errorMsg('This email is not registered with us');
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to admin logout
    public function logout(Request $request)
    {
        try{
            Auth::logout();
            return redirect('/');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
