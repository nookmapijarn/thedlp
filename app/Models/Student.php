<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student';
    protected $fillable = [
        'id',
        'school',
        'std_code',
        'prenameid',
        'prename',
        'name',
        'midname',
        'surname',
        'grp_code',
        'gender',
        'age',
        'birday',
        'religion',
        // 'occp',
        // 'occtyp',
        // 'crippleid',
        // 'tall',
        // 'weight',
        // 'brother',
        // 'studybro',
        'fa_prename',
        'fa_name',
        'fa_surname',
        // 'fa_cripple',
        // 'fa_status',
        // 'fa_salary',
        // 'fa_occp',
        'mo_prename',
        'mo_name',
        'mo_surname',
        // 'mo_cripple',
        // 'mo_status',
        // 'mo_salary',
        // 'mo_occp',
        // 'marrystat',
        'pa_prename',
        'pa_name',
        'pa_surname',
        // 'pa_salary',
        // 'pa_occp',
        // 'expertid',
        // 'expert',
        // 'method',
        // 's_grad',
        // 's_year',
        // 's_school',
        // 's_province',
        // 't_grad',
        // 't_year',
        // 't_school',
        // 't_province',
        'houseid',
        'addr',
        'tambonid',
        'zipcode',
        'curaddr',
        'ctambonid',
        'czipcode',
        'dep_sem',
        'app_date',
        'fin_date',
        'trscp_date',
        'fin_sem',
        'fin_cause',
        // 'fin_date2',
        // 'trn_date2',
        // 'fin_sem2',
        // 'cfin_cause',
        // 'act_sem',
        // 'act_hour',
        'nation',
        'card',
        'trngrp',
        'trnrun',
        'certnum',
        'cardid',
        'budgettyp',
        'phone',
        'curphone',
        'email',
        'ablevel1',
        'expflag',
        'expsem',
        'ablevel2',
        // 'lastupdate',
        // 'insertdate',
        'nt_sara1',
        'nt_sara2',
        'nt_sem',
        'nt_nosem',
        // 'pinidrem',
        // 'picfile',
        // 'coupon',
        // 'gpasem',
        // 'gpatype',
        // 'ablev1_sem',
        // 'appltype',
        // 'v_status',
        // 'v_senddate',
        // 'v_recvdate',
        // 'v_recvdoc',
        // 'v_folder',
        // 'v_school',
        // 'v_schstat',
        // 'v_reqdate',
        // 'v_reqdoc',
        // 'v_repdate',
        // 'v_retdoc',
        // 'v_retdate',
        // 'subtype',
        // 'subfield',
        // 'branch',
        'learning',
        // 'dualschool',
        'trnseries',
        // 'disadv'
    ];
}