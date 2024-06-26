<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->has('file')) {
            $name = fileUpload($request->file, "/uploads/$request->type");
            if($request->filled('id')){
                $image = new Image;
                $image->item_name = $name;
                $image->item_id = $request->id;
                $image->item_type = $request->type;
                $image->save();
            }
            return response()->json(['status'=> true, 'file_name'=> $name, 'key'=> 1]);  
        }
    }

    public function deleteImage(Request $request)
    {
        $path = public_path("uploads/$request->type/$request->filename");
        if (File::exists($path)) {
            fileRemove("/uploads/$request->type/$request->filename");
            if($request->filled('id')){
                Image::where('item_name', $request->filename)->where('item_id', $request->id)->where('item_type', $request->type)->delete();
            }
            return response()->json(['status'=>true, 'file_name'=> $request->filename, 'key'=> 2]); 
        }
        return response()->json(['status'=>false, 'file_name'=> $request->filename, 'key'=> 2]);   
    }

    public function uploadDeleteImage($id, $type)
    {
        $id = encrypt_decrypt('decrypt', $id); 
        $imgs = Image::where('id', $id)->where('item_type', $type)->first();
        $count = Image::where('item_id', $imgs->item_id)->where('item_type', $type)->count();
        if($count == 1) return redirect()->back()->with('error', "Minimum one image must be required. Can't Remove");
        fileRemove("uploads/$imgs->item_type/$imgs->item_name");
        Image::where('id', $id)->where('item_type', $type)->delete();
        return redirect()->back()->with(['success'=> 'Image removed successfully']);
    }
}
