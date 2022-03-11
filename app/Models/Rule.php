<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Uspdev\Replicado\DB as ReplicadoDB;

class Rule extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function quota_period_options()
    {
        return [
            'Mensal',
            'Diário',
        ];
    }

    public static function categories()
    {
        $sql = 'SELECT DISTINCT (tipvin) FROM LOCALIZAPESSOA ORDER BY tipvin';
        $result = ReplicadoDB::fetchAll($sql);
        if ($result) {
            return array_column($result, 'tipvin');
        }

        return;
    }

    public function printers()
    {
        return $this->hasMany(Printer::class);
    }

    public function setCategoriesAttribute($value)
    {
        $this->attributes['categories'] = implode(',', $value);
    }

    public function getCategoriesAttribute($value)
    {
        if ($value) {
            return explode(',', $value);
        }

        return [];
    }
}
