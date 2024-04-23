<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class valueset extends Model
{
    use HasFactory;

    protected $fillable = [
        'value_set_id',
        'value_set_name',
        'medications'
    ];

    public function getMedicationListAttribute()
    {
        return explode('|', $this->medications);
    }
}
