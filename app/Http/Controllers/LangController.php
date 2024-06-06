<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LangController extends Controller
{
    public function change(Request $request){
		$app_lang = config('app.locale');
		session()->put('locale', $request->lang);
		$envpath = base_path('.env');
		$envdata = file_get_contents($envpath);
		if (file_exists($envpath)) {
		   file_put_contents($envpath, str_replace("APP_LOCALE=$app_lang", "APP_LOCALE=$request->lang", $envdata));
		}
        return redirect()->back();

    }
}
