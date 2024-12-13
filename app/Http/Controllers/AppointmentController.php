<?php

namespace App\Http\Controllers;

use App\Models\Checkup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(){
        $checkups = Checkup::whereUserId(Auth::user()->id)->get();
        return view('site.appointment', compact('checkups'));
    }
}
