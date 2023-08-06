<?php

namespace App\Http\Controllers;

use App\Models\Cidades;
use App\Http\Resources\CidadesResource;

class CidadesController extends Controller
{
    public function index()
    {
        $cidades = Cidades::all();

        return CidadesResource::collection($cidades);
    }
}
