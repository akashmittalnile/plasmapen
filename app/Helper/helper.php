<?php 


use App\Mail\DefaultMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;



// Dev name : Dishant Gupta
// This function is used to encrypt decrypt data
if (!function_exists('encrypt_decrypt')) {
    function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'PlasmaPenWebAppli';
        $secret_iv = 'PlasmaPenWebAppli';
        // hash
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}

// Dev name : Dishant Gupta
if (!function_exists('successMsg')) {
    function successMsg($msg, $data = [])
    {
        return response()->json(['status' => true, 'message' => $msg, 'data' => $data]);
    }
}

// Dev name : Dishant Gupta
if (!function_exists('errorMsg')) {
    function errorMsg($msg, $data = [])
    {
        return response()->json(['status' => false, 'message' => $msg, 'data' => $data]);
    }
}

// Dev name : Dishant Gupta
// This function is used to change the existing function asset because of its path
if (!function_exists('assets')) {
    function assets($path)
    {
        return asset('public/' . $path);
    }
}

// Dev name : Dishant Gupta
// This function is used to send mail
if (!function_exists('sendEmail')) {
    function sendEmail($data)
    {
        $data['from_email'] = env('MAIL_FROM_ADDRESS');
        Mail::to($data['to_email'])->send(new DefaultMail($data));
    }
}

// Dev name : Dishant Gupta
// This function is used to check email address is exist or not 
if (!function_exists('emailExist')) {
    function emailExist($email)
    {
        $exist = User::where('email', $email)->whereIn('status', [0, 1, 2, 3])->first();
        if (isset($exist->id)) return true;
        else false;
    }
}

// Dev name : Dishant Gupta
// This function is used to save a file
if (!function_exists('fileUpload')) {
    function fileUpload($file, $path)
    {
        $name = $file->getClientOriginalName();
        $file->move(public_path("$path"), $name);
        return $name;
    }
}

// Dev name : Dishant Gupta
// This function is used to remove a file
if (!function_exists('fileRemove')) {
    function fileRemove($path)
    {
        $link = public_path("$path");
        if (File::exists($link)) {
            unlink($link);
        }
    }
}

// Dev name : Dishant Gupta
// This function is used to remove a file
if (!function_exists('translate')) {
    function translate($string){
        return __($string);
    }
}

if (!function_exists('array_has_dupes')) {
    function array_has_dupes($array)
    {
        return count($array) !== count(array_unique($array));
    }
}

// Dev name : Dishant Gupta
// This function is used to push notification using firebase
if (!function_exists('sendNotification')) {
    function sendNotification($token, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FIREBASE_SERVER_KEY'); // ADD SERVER KEY HERE PROVIDED BY FCM
        $msg = array(
            'body'  => $data['msg'] ?? 'NA',
            'title' => $data['title'] ?? "Journey with Journals",
            'icon'  => "{{ assets('assets/images/logo2.svg') }}", //Default Icon
            'sound' => 'default'
        );
        $arr = array(
            'to' => $token,
            'notification' => $msg,
            'data' => $data,
            "priority" => "high"
        );
        $encodedData = json_encode($arr);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
    }
}

?>