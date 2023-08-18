<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\Printing;
use App\Models\User;

class Printer extends Model #implements \Rawilk\Printing\Contracts\Printer
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

    public function allows(User $user)
    {
        if (!empty($this->rule) and !empty($this->rule->categories)) {
            foreach($this->rule->categories as $c) {
                if($user->hasPermissionTo($c, 'senhaunica')) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    public function used(User $user)
    {
        if (!empty($this->rule))
        {
            $period = $this->rule->quota_period;
            if (!empty($period)) {
                return Printing::getPrintingsQuantities($user->codpes, $this, $period);
            }
        }
        return;
    }
}
