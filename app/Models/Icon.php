<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Icon extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $guarded=[];

    public function setIconAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting icon extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('icon/'), $filename);
            $this->attributes['icon'] =  'icon/'.$filename;
        }
    }
    protected static function booted()
    {
        static::deleted(function ($icon) {
            if ($icon->icon  && \Illuminate\Support\Facades\File::exists($icon->icon)) {
                unlink($icon->icon);
            }
        });
    }

}
