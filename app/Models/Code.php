<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function address(){
        return $this->hasOne(Address::class);
    }

    public function getNameAttribute(){
        return $this->address->first()->name;
    }
    // $object->name with out key in data base

}
