<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\Printing;
use App\Models\User;
use Spatie\Permission\Models\Permission;

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

    public function allows(User $user)
    {
        if (!empty($this->rule) and !empty($this->rule->categories)) {
            foreach($this->rule->categories as $c) {
                foreach (['web', 'senhaunica'] as $guard) {
                    $p = Permission::where([
                        'name' => $c,
                        'guard_name' => $guard
                    ])->first();
                    if ($p and $user->hasPermissionTo($p)) {
                        return true;
                    }
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
            $type = $this->rule->quota_type;
            if (!empty($period)) {
                return Printing::getPrintingsQuantities($user->codpes, $this, $period, $type);
            }
        }
        return;
    }
}
