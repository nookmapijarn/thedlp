<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';
    protected $fillable = [
        'std_code',
        'activity',
        'semestry',
        'hour',
        // 'transfer',
        // 'trntype'
    ];
}