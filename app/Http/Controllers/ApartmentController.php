<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use App\Http\Requests\StoreApartmentListingRequest;
use Validator;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{   
     /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $apartments = Apartment::orderBy('id')->latest()->get();

        return [
            'status' => 'success',
            'total record' => count($apartments),
            'apartments'=> $apartments,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'category' => 'required',
            'number_of_guests' => 'required|integer',
            'number_of_bedrooms' => 'required|integer',
            'number_of_kitchens' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->toJson()], 400);
        }

        $data = $request->all();

        $apartment = Apartment::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Apartment created successfully, Please proceed to add images to this record',
            'apartment'=> $apartment,
        ], 201);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $apartment = Apartment::find($id);

        if(!$apartment){
            return response()->json([
                'message'=> 'Apartment not found',
            ]);
        }

        return response()->json([
            'Total file upload' => !empty($apartment->files) ? count($apartment->files) : 0,
            'apartment'=> $apartment,
            
            // 'images' => $apartment->files,
        ]);

    }

    /*
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request->all();

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'category' => 'required',
            'number_of_guests' => 'required|integer',
            'number_of_bedrooms' => 'required|integer',
            'number_of_kitchens' => 'required|integer',
            'amount' => 'required|numeric',
            'caution_fee' => 'nullable|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->toJson()], 422);
        }

        $apartment = Apartment::find($request->id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        $updated = Apartment::where('id', $request->id)->update($request->all());

        $updated_apartment = Apartment::find($request->id);

       if($updated){
            return response()->json([
                'status' => true,
                'message' => "Apartment Updated successfully! Please",
                'apartment' => $updated_apartment
            ], 200);
       }else{
        response()->json([
            'status' => true,
            'message' => "Nothing to update",
        ]);
       }
    }

    public function delete($id){
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        $res = $apartment->delete();

        if($res){
            return response()->json(['message'=> 'Apartment deleted successfully'],200);
        }

    }

    public function search($params){
        $apartments = Apartment::where('category', 'like', '%'.$params.'%' )->get();

        return response( )->json([
            'message' => 'Search result for: '.$params,
            'Result' => 'Total result found is: '.count($apartments),
            'Apartments'=> $apartments
        ]);
    }

    public function upload(Request $request){

        $apartment = Apartment::find($request->apartment_id);

        if(empty( $apartment)){
            return response()->json(['message'=> 'Record not found'], 404);
        }

        if(!$request->hasFile('file')) {
            return response()->json(['upload file not found'], 400);
        }

        $validator = Validator::make($request->all(), [
            'apartment_id' => 'required|numeric',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->toJson()], 400);
        }
        
        // $allowedFileExtension = ['jpeg','jpg','png'];
        $file = $request->file; 
        $fileName = $file->hashName() ;
        $destinationPath = public_path().'/images' ;
        $file->move($destinationPath,$fileName);
        
        //store image file into directory and db
        $fileUpload = new UploadFile();
        $fileUpload->apartment_id = $request->apartment_id;
        $fileUpload->file = $fileName;
        $res = $fileUpload->save();

        $retVal = (!empty($apartment->files) && count($apartment->files) < 4) ? 'Please, minimum of 4 files required' :'';

        return response()->json([
            'message' => 'File added successfully! '.$retVal,
            'Total upload' => !empty($apartment->files) ? count($apartment->files) : 0,
            'status' => 'success',
            'file' => $fileUpload
        ], 200);

    }
}
