<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\Printer;
use App\Models\Status;


class Printing extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at'];

    /*
    public function scopeAllowed($query)
    {
        $user = Auth::user();
        if (!Gate::allows('admin')) {
            $query->OrWhere('owner', '=', $user->codpes);
            // melhorar essa query!!! está insegura
            $query->OrWhere('numeros_usp', 'LIKE', '%'.$user->codpes.'%');
            return $query;
        }
        return $query;
    }*/

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function status()
    {
    	return $this->hasMany(Status::class);
    }

    public function latest_status()
    {
    	return $this->hasOne(Status::class)->latest();
    }
    
    public function printing_quantities($type, $subject=null, $id=null)
    {
        // subject: pode ser user ou de todo o banco (na função antiga tinha por impressora tb)
        // type: total, hoje ou mes
        
        $quantities = collect([$type => 0]);
        // if (!$subject): $all = Printing::all();
        // if ($subject == "user"): $all =  Printing::where('user', $user)->all()
        // if ($subject == "printer"): $all = Printing::where('printer_id', $id)->all()

        // total de impressoes
        foreach ($total as $printing) {
            if ($printing->latest_status()->first()->name == 'print_success') {
                $quantities[$type] = $quantities[$type] + (int)$printing->pages*(int)$printing->copies;
                }
            }
            
        return $quantities;
    }    
}

