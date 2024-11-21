<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'old_email',
        'new_email',
        'changed_at',
        'token',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
