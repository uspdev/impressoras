<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function printings()
    {
        return $this->hasMany(Printing::class);
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }
}
