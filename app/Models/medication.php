<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_id',
        'medname',
        'simple_generic_name',
        'route',
        'outpatients',
        'inpatients',
        'patients'
    ];
}
