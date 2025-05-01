<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Importação necessária

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
