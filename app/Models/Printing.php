<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printing extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at'];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function status()
    {
        return $this->hasMany(Status::class);
    }

    public function authorizedByUserId()
    {
        return $this->belongsTo(User::class, 'authorized_by_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
