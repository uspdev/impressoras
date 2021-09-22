<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Printing;

class Status extends Model
{
    use HasFactory;
    protected $table = "status";

    public function printing()
    {
    	return $this->belongsTo(Printing::class);
    }
}
