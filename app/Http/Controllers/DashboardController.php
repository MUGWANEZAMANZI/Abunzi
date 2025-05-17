<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
         

        $user = auth()->user();
       
        return match ($user->role) {
            'citizen' => redirect()->route('citizen.dashboard'),
            'justice' => redirect()->route('justice.dashboard'),
            'chief' => redirect()->route('chief.dashboard'),
            default => abort(403, 'Unauthorized access'),
        };
    }
}

