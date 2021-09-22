<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Printers;

class Rule extends Model
{
    use HasFactory;

    public function printers()
	{
		return $this->hasMany(Printer::class);
	}
}
