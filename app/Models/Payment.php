<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['amount'];
    public function user()  {
        return $this->belongsTo(User::class);
        
    }

    public function paymentType()  {
        return $this->belongsTo(PaymentType::class);
        
    }
}
