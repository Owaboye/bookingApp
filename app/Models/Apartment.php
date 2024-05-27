<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UploadFile;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'number_of_guests',
        'number_of_bedrooms',
        'number_of_kitchens',
        'amount',
        'caution_fee',
    ];

    public function files(){
        return $this->hasMany(UploadFile::class);
    }
}
