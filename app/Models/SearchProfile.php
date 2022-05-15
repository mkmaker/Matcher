<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchProfile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $directMatchFields = [
        'parking_available',
        'heating_type',
    ];

    protected $casts = [
        'search_fields' => 'array'
    ];

    public function isADirectMatchField($field)
    {
        return in_array($field, $this->directMatchFields);
    }
}
