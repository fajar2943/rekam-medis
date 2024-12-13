<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


// function getSetting($name){
// 	return Cache::rememberForever("setting_$name", function() use($name){
// 		return Setting::whereName($name)->first();
// 	}); 
// }

function uriActive($name, $activeClass = "active") {
	if(is_array($name)){
		foreach($name as $n){
			if(request()->is($name)) return $activeClass;
		}
	}
	return request()->is($name) ? $activeClass : '';
}
function isUri($name) {
	if(is_array($name)){
		foreach($name as $n){
			if(request()->is($name)) return true;
		}
	}
	return request()->is($name);
}
function isReq($name, $compare) {
	return request()->$name == $compare;
}
function authAs($compare = null){
	if(!Auth::user()) return null;
	if(!$compare) return Auth::user()->role;
	return Auth::user()->role == $compare;
}
function isSelected($name, $value){
	return $name == $value ? 'selected' : '';
}

function rupiah($amount){
    return 'Rp '.number_format($amount, 0, ',', '.');
}
function tanggal($tanggal){
    return date('d M Y', strtotime($tanggal));
}
function tglwaktu($tanggal){
    return date('d M Y H:i', strtotime($tanggal));
}
function hari($start, $finish){
    $day = strtotime(date('Y-m-d', strtotime($finish))) - strtotime(date('Y-m-d', strtotime($start)));
    return ($day / 60 / 60 / 24) + 1;
}
function angka($amount){
    return number_format($amount, 0, ',', '.');
}
function setImage(Request $request, $data, $collection_name, $req_file_name = 'image'){
	if($request->hasFile($req_file_name)){
		$data->clearMediaCollection($collection_name);
		$data->addMediaFromRequest($req_file_name)->toMediaCollection($collection_name);
	}
}