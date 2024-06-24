<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    public $timbestampe = true;
    protected $fillable = ['applicant' , 'detailes'];
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
}