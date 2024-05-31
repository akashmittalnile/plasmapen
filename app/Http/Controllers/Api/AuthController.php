<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to register user
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'password' => 'required',
                'country_code' => 'required',
                'mobile' => 'required',
                'email' => 'required|email',
                'file' => 'required|image',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                if (!emailExist($request->email)) {
                    $user = new User;
                    if ($request->hasFile("file")) {
                        $name = fileUpload($request->file, "/uploads/profile/");
                        $user->profile = $name;
                    }
                    $user->name = ucwords($request->name);
                    $user->email = strtolower($request->email);
                    $user->country_code = $request->country_code ?? null;
                    $user->mobile = $request->mobile ?? null;
                    $user->password = Hash::make($request->password);
                    $user->role = 2;
                    $user->email_verified_at = 1;
                    $user->status = 1;
                    $user->updated_at = date('Y-m-d H:i:s');
                    if (isset($request->fcm_token)) {
                        $user->fcm_token = $request->fcm_token;
                    }
                    $user->save();
                    // $data['subject'] = 'Welcome to Plasma Pen';
                    // $data['site_title'] = 'Welcome to Plasma Pen';
                    // $data['view'] = 'pages.user.email.registration-successful';
                    // $data['to_email'] = $request->email;
                    // $data['customer_name'] = $user->name;
                    // sendEmail($data);
                    $token = $user->createToken("plasma_pen")->plainTextToken;
                    return successMsg('Registered successfully.', ['access_token' => $token]);
                } else return errorMsg('This email is already exist!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to login in app
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->first();
                if (isset($user->id)) {
                    if ($user->status == 1) {
                        if (Hash::check($request->password, $user->password)) {
                            $token = $user->createToken("plasma_pen")->plainTextToken;
                            if (isset($request->fcm_token)) {
                                User::where('email', $request->email)->where('id', $user->id)->update([
                                    'fcm_token' => $request->fcm_token
                                ]);
                            }
                            $response = array('user' => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'country_code' => $user->country_code,
                                'mobile' => $user->mobile,
                                'profile_image' => isset($user->profile) ? assets('uploads/profile/' . $user->profile) : null,
                                'role' => $user->role,
                                'status' => $user->status,
                                'fcm_token' => $user->fcm_token,
                                'created_at' => date('d M, Y h:i A', strtotime($user->created_at)),
                            ], 'access_token' => $token);
                            return successMsg('Logged In Successfully.', $response);
                        } else  return errorMsg('Invalid Email or Password!');
                    } else return errorMsg('Your account was temporarily inactive by administrator!');
                } else return errorMsg('This account is not registered with us!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to sending the otp to email address for reset password
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->first();
                if (isset($user->id)) {
                    $code = rand(1000, 9999);
                    $user->otp = $code;
                    $user->updated_at = date('Y-m-d H:i:s');
                    // $data['subject'] = 'Reset Your plasma pen password';
                    // $data['site_title'] = 'Reset Your plasma pen password';
                    // $data['view'] = 'pages.user.email.send-otp';
                    // $data['to_email'] = $request->email;
                    // $data['otp'] = $code;
                    // $data['customer_name'] = $user->name;
                    // sendEmail($data);
                    $user->save();
                    return successMsg('OTP sent to your email address', ['otp' => $code]);
                } else return errorMsg('Email is not registered with us');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to verify the otp is correct or not for reset password
    public function otpVerification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
                if (isset($user->id)) {
                    if (date("Y-m-d H:i:s", strtotime("+600 sec", strtotime($user->updated_at))) >= date('Y-m-d H:i:s')) {
                        return successMsg('OTP verified successfully.');
                    } else return errorMsg('Timeout. Please try again...');
                } else return errorMsg('Wrong OTP Entered!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update the password
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
                if (isset($user->id)) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return successMsg('Password has been changed successfully');
                } else return errorMsg('Wrong OTP Entered!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the particular user all details
    public function profile(Request $request)
    {
        try {
            $user = Auth::user();
            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'country_code' => $user->country_code ?? null,
                'mobile' => $user->mobile ?? null,
                'role' => $user->role,
                'status' => $user->status,
                'fcm_token' => $user->fcm_token,
                'profile_image' => isset($user->profile) ? assets('uploads/profile/' . $user->profile) : null,
                'created_at' => date('d M, Y h:i A', strtotime($user->created_at))
            ];
            return successMsg('Profile data', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update the profile data of user
    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'mobile' => 'required',
                'country_code' => 'required',
                'file' => 'image',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('id', auth()->user()->id)->first();
                if (isset($user->id)) {
                    if ($request->hasFile("file")) {
                        if (isset($user->profile)) {
                            // fileRemove("/uploads/profile/" . $user->profile);
                        }
                        $name = fileUpload($request->file, "/uploads/profile/");
                        $user->profile = $name;
                    }
                    $user->name = ucwords($request->name);
                    $user->country_code = $request->country_code;
                    $user->mobile = $request->mobile;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();
                    return successMsg('Profile updated successfully');
                } else return errorMsg('Invalid user!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change the password
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('id', auth()->user()->id)->first();
                if (isset($user->id)) {
                    if (Hash::check($request->old_password, $user->password)) {
                        if (!Hash::check($request->new_password, $user->password)) {
                            $user->password = Hash::make($request->new_password);
                            if ($user->save()) {
                                return successMsg('Password has been changed successfully');
                            }
                        } else return errorMsg('New password cannot same as old password.');
                    } else  return errorMsg('Old password is incorrect.');
                } else return errorMsg('Invalid user!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to user logging out
    public function logout()
    {
        try {
            User::where('id', auth()->user()->id)->update([
                'fcm_token' => null
            ]);
            Auth::user()->tokens()->delete();
            return successMsg('Logout successfully.');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show error message when bearer token expired
    public function tokenExpire()
    {
        try {
            return errorMsg('Token expired! Please login....');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
