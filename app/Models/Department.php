<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    public $timestamps = true;
    
    protected $fillable=['email','phone','adress','name'];
    
    public  function employees(){
        return $this->hasMany(Employee::class);
    }
    public  function projects(){
        return $this->hasMany(Project::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
}