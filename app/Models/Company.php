<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Company extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory, HasTranslations;
    protected $guarded=[];
    public $translatable = ['name','address','description','short_desc'];

    public function setLogoAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('logo/'), $filename);
            $this->attributes['logo'] =  'logo/'.$filename;
        }
    }


    public function setCoverPictureAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('pic/'), $filename);
            $this->attributes['cover_picture'] =  'pic/'.$filename;
        }
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }

    protected static function booted()
    {
        static::deleted(function ($company) {
            if($company->images) $company->images()->delete();
            if ($company->logo  && \Illuminate\Support\Facades\File::exists($company->logo)) {
                unlink($company->logo);
            }
            if ($company->cover_picture  && \Illuminate\Support\Facades\File::exists($company->cover_picture)) {
                unlink($company->cover_picture);
            }
        });
    }
}
