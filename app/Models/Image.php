<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;

    protected $fillable=['image','company_id','address_id'];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/'), $filename);
            $this->attributes['image'] =  'img/'.$filename;
        }
    }

    public function address(){
        return $this->belongsTo(Address::class,'address_id');
    }


    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }

    protected static function booted()
    {
        static::deleted(function ($image) {
            if ($image->image  && \Illuminate\Support\Facades\File::exists($image->image)) {
                unlink($image->image);
            }
        });
    }
}
