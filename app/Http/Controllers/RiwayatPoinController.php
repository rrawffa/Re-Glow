<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiwayatPoinController extends Controller
{
    public function index()
    {
        return view('riwayat poin.poinhistory');
    }
}
