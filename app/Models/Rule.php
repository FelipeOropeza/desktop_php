<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'source_folder',
        'destination_folder',
        'destination_subfolder',
        'extension',
        'keyword',
        'active'
    ];
}
