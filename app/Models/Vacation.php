<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=['duration','type','end_date','start_date'];
    public function employee()  {
       return $this->belongsTo(Employee::class);
        
    }
}