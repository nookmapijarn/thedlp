<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastModifiedFile extends Model
{
    use HasFactory;
    protected $table = 'lastmodifiedfile';
    protected $fillable = [
        'file_name',
        'lavel',
        'last_modified',
        'uploaded',
    ];
}
