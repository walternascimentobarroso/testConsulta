<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'paciente';

    protected $fillable = ['nome', 'cpf', 'celular'];

    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'medico_paciente', 'paciente_id', 'medico_id');
    }
}
