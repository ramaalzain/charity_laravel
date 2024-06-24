<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;
    public $timbestamps = true;
    protected $fillable = ['name'];
    public function users(){
        return $this->hasMany(User::class);
    }
}