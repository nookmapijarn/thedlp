<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';
    protected $fillable = [
        'grp_code',
        'grp_name',
        'grp_abname',
        'grp_advis',
        'grp_field',
        'grp_meet',
        'grp_class',
        'grp_learn',
        'grp_num',
        'grp_size',
        'tambonid',
        'amphurid',
        'province',
        // 'coupon',
        // 'igrp_code'
    ];
}