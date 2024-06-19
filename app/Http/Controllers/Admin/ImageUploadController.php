<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->has('file')) {
            $name = fileUpload($request->file, "/uploads/$request->type");
            return response()->json(['status'=> true, 'file_name'=> $name, 'key'=> 1]);  
        }
    }

    public function deleteImage(Request $request)
    {
        $path = public_path("uploads/$request->type/$request->filename");
        if (File::exists($path)) {
            fileRemove("/uploads/$request->type/$request->filename");
            return response()->json(['status'=>true, 'file_name'=> $request->filename, 'key'=> 2]); 
        }
        return response()->json(['status'=>false, 'file_name'=> $request->filename, 'key'=> 2]);   
    }
}
