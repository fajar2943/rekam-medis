<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolyDoctor extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function poly(){
        return $this->belongsTo(Poly::class);
    }
}
