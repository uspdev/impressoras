<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Printers;
use App\Models\Printer;

class Rule extends Model
{
    use HasFactory;
    protected $guarded = ['id'];	

    public static function types_of_control()
    {
        return [
            'Mensal',
            'Diário',
        ];
    }    

    public function printers()
	{
		return $this->hasMany(Printer::class);
	}
}
