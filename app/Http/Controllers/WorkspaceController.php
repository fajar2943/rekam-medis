<?php

namespace App\Http\Controllers;

use App\Models\Checkup;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index(){
        $checkups = Checkup::whereIn('schedule_id', Schedule::whereUserId(Auth::user()->id)->pluck('id'))->paginate(10);
        return view('site.workspace', compact('checkups'));
    }
}
