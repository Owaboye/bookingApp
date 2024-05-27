<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadFile;

class UploadFileController extends Controller
{
    public function store(Request $request){
        $files = $_FILES["files"];

        if(count($files['tmp_name']) < 4){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Minimum of 4 file uploads'
            ]);
        }
        // return $files;

        $path = public_path().'/uploads/';
        $fileArr = [];

        for($i = 0; $i < count($files['tmp_name']); $i++){
            // $file = $files[$i];
            array_push($fileArr, $files['name'][$i]);

            move_uploaded_file($files['tmp_name'][$i], $path.$files['name'][$i]);

            $fileUpload = new UploadFile();
            $fileUpload->apartment_id = $request->apartment_id;
            $fileUpload->file = $files['name'][$i];
            $res = $fileUpload->save();
        }

        return response()->json([
            'status'=> 'success',
            'message'=> 'File uploaded successfully'
        ]);
    }
}
