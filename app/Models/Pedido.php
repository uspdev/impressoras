<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id', 
        'user_id', 
        'codpes', 
        'quantidade', 
        'quantidade_usada', 
        'motivo'
    ];

    public static function status($pedido)
    {
        $list = [
            'accepted' => 'Aceito',
            'refused' => 'Recusado',
            'waiting' => 'Aguardando processamento'
        ];

        return $list[$pedido];

    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }
}
