<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * methods from \Rawilk\Printing\Contracts\Printer
     **/ 
    /*
    public function capabilities(): array {
        return ['aaa'];
    }

    public function description(): ?string {
        return 'bla';
    }

    public function id(){
        return 4;
    }

    public function isOnline(): bool {
        return true;
    }

    public function name(): ?string {
        //return $this->name;
        return 'testessss';
    }

    public function status(): string {
        return 'bla 2';
    }

    public function trays(): array {
        return [];
    }

    //public function jobs(): Collection;
    public function jobs(): Collection {
        return collect([]);
    }
    */
}
