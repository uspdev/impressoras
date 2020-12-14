<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public function user(){
        return $this->belongsTo('App\User');
    }
}

