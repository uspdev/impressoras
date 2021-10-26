<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Printers;
use App\Models\Printer;
use Uspdev\Replicado\DB as ReplicadoDB;

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

    public static function categorias()
    {
        $sql = "SELECT DISTINCT (tipvin) FROM LOCALIZAPESSOA ORDER BY tipvin";
        $result = ReplicadoDB::fetchAll($sql);
        if($result) return array_column($result, 'tipvin');
        return ;
    }

    public function printers()
	{
		return $this->hasMany(Printer::class);
	}

    public function setCategoriasAttribute($value){
        $this->attributes['categorias'] = implode(',',$value);
    }
    
    public function getCategoriasAttribute($value){
        if($value) return explode(',',$value);
        return [];
    }
}
