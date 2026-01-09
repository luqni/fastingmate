<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('posts', 'public');
            
            return response()->json([
                'location' => asset('storage/' . $path),
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
