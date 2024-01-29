<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * get all albums
     * GET /api/albums
     */
    public function index()
    {
        try {
            return $albums = Album::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500,
            ],500);
        }
    }
}
