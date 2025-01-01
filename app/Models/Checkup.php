<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Checkup extends Model
{
    use Searchable;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
    public function details(){
        return $this->hasMany(Detail::class);
    }
    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'complaint' => $this->complaint,
        ];
    }
    public function checkup_status(){
        if($this->status == 'checkup') return 'Pemeriksaan';
        if($this->status == 'waiting_medicine') return 'Menunggu Obat';
        if($this->status == 'done') return 'Selesai';
        if($this->status == 'canceled') return 'Di Batalakan';
    }
}
