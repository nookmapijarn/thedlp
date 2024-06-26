<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';
    protected $fillable = [
        'GRP_CODE',
        'GRP_NAME',
        'GRP_ABNAME',
        'GRP_ADVIS',
        'GRP_FIELD',
        'GRP_MEET',
        'GRP_CLASS',
        'GRP_LEARN',
        'GRP_NUM',
        'GRP_SIZE',
        'TAMBONID',
        'AMPHURID',
        'PROVINCE',
        'COUPON',
        'IGRP_CODE',
        'UPDATED_AT',
        'CREATED_AT'
    ];
}