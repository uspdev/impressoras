<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
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
        $c = [];
        foreach (Permission::all() as $p) {
            array_push($c, $p->name);
        }
        return $c;
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
