<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Printing;

class Printer extends Model
{
	use HasFactory;
	
	public function printings()
	{
		return $this->hasMany(Printing::class);
	}
}
