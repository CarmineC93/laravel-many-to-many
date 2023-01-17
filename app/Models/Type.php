<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    //nome della funzione plurale perche in un tipo si possono avere piu progetti
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
