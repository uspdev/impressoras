<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Printer;
use App\Models\Status;

class Printing extends Model
{
    use HasFactory;

    public function scopeAllowed($query)
    {
        $user = Auth::user();
        if (!Gate::allows('admin')) {
            $query->OrWhere('owner', '=', $user->codpes);
            // melhorar essa query!!! está insegura
            $query->OrWhere('numeros_usp', 'LIKE', '%'.$user->codpes.'%');
            return $query;
        }
        return $query;
    }

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function status()
    {
    	return $this->hasMany(Status::class);
    }

    public function latest_status()
    {
    	return $this->hasOne(Status::class)->latest();
    }
}

