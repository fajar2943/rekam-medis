<?php

namespace App\Http\Controllers;

use App\Models\Checkup;
use App\Models\Poly;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index(Request $request){
        $schedules = Schedule::whereUserId(Auth::user()->id)->get();
        $polies = Poly::all();
        
        $statuses = $request->statuses ? [$request->statuses] : ['checkup'];
        $statuses = $request->statuses == 'all' ? ['waiting_checkup', 'checkup', 'waiting_medicine', 'done', 'canceled'] : $statuses;
        $checkups = Checkup::whereIn('status', $statuses)->whereIn('schedule_id', Schedule::whereUserId(Auth::user()->id)->pluck('id'))->paginate(10);
        return view('site.workspace', compact('checkups', 'schedules', 'polies'));
    }

    public function schedule(Request $request) {
        $request->validate([
            'poly_id' => 'required|max:255',
            'day' => 'required|max:255',
            'start' => 'required|max:255',
            'finish' => 'required|max:255',
        ]);
        $request['user_id'] = Auth::user()->id;
        if(Schedule::whereUserId(Auth::user()->id)->where('day', $request->day)->count()){
            return back()->with('failed', 'Mohon maaf, anda tidak boleh membuat jadwal di hari yang sama.')->withInput();
        }
        Schedule::create($request->only('user_id', 'poly_id', 'day', 'start', 'finish'));
        return back()->with('success', 'Data created.');
    }

    public function switch_status(Request $request){
        Schedule::whereUserId(Auth::user()->id)->update(['status' => false]);
        $request['status'] = $request->status ? true : false;
        Schedule::find($request->id)->update($request->only('status'));
        return back();
    }

    public function history(User $user){
        $checkups = Checkup::whereUserId($user->id)->paginate(10);
        return view('site.history', compact('user', 'checkups'));
    }

    // pharmacy

    public function pharmacy(Request $request){
        $statuses = $request->statuses ? [$request->statuses] : ['waiting_medicine'];
        $statuses = $request->statuses == 'all' ? ['waiting_checkup', 'checkup', 'waiting_medicine', 'done', 'canceled'] : $statuses;
        $checkups = Checkup::whereIn('status', $statuses)->paginate(10);
        return view('site.pharmacy', compact('checkups'));
    }
}
