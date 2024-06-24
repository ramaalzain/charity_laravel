<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;
    public $timbestamps = true;
    protected $fillable = ['type'];
    public function payments(){
        return $this->hasMany(Payment::class);
    }
    
}
