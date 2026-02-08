<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class IbadahController extends Controller
{
    public function morning()
    {
        $path = database_path('data/dhikr-morning.json');
        $dhikrs = json_decode(File::get($path), true);
        
        return view('ibadah.dhikr', [
            'title' => 'Dzikir Pagi',
            'type' => 'morning',
            'dhikrs' => $dhikrs
        ]);
    }

    public function evening()
    {
        $path = database_path('data/dhikr-evening.json');
        $dhikrs = json_decode(File::get($path), true);

        return view('ibadah.dhikr', [
            'title' => 'Dzikir Petang',
            'type' => 'evening',
            'dhikrs' => $dhikrs
        ]);
    }
}
