<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpRequestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_request_id',
        'user_id',
        'status',
        'action_detail',
        'note',
    ];

    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
