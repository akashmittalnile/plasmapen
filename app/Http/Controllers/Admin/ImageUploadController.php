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
            $file = $request->file('file');
            $image = $file->getClientOriginalName();
            // $file->move('uploads/products/', $image);
            fileUpload($request->file, "/uploads/blog");
            return $image;
        }
    }

    public function deleteImage(Request $request)
    {
        $path = public_path('uploads/blog/' . $request->filename);
        if (File::exists($path)) {
            fileRemove("/uploads/blog/$request->filename");
            return $request->filename;
        }
    }
}
