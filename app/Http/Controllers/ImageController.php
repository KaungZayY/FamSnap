<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ImageController extends Controller
{
    /**
     * get all images and filter by album
     * GET /api/images
     * @param album_id - optional
     */
    public function index()
    {
        try {
            return Image::filter(request(['album_id']))->paginate(4);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ],500);
        }
    }

    public function store()
    {
        try {
            $validator = Validator::make(request()->all(),[
                'title' => 'required',
                'image' => 'required',
                'album_id' => ['required',Rule::exists('albums','id')],
            ]);
            if($validator->fails()){
                $flattenedErrors = collect($validator->errors())->flatMap(function ($e,$field){
                    return [$field=>$e[0]];
                });
                return response()->json([
                    'message' => $flattenedErrors,
                    'status' => 400
                ],400);
            }

            //if valid
            $image = new Image;
            $image->title = request()->title;
            $image->image = request()->image;
            $image->album_id = request()->album_id;
            $image->save();
            return response()->json($image,201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ],500);
        }
    }

    /**
     * get single image
     * GET /api/images/:id
     * @param image_id
     */
    public function show($id)
    {
        try {
            $image = Image::findOrFail($id);
            return $image;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ],404);
        }
    }
}
