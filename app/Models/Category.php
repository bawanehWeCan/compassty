<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory, HasTranslations;
    protected $guarded=[];
    public $translatable = ['name'];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/category/'), $filename);
            $this->attributes['image'] =  'img/category/'.$filename;
        }
    }

    public function companies(){
        return $this->hasMany(Company::class);
    }

}
