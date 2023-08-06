<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;
use App\Http\Resources\MedicoResource;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::all();

        return MedicoResource::collection($medicos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'especialidade' => 'required|string|max:100',
            'cidade_id' => 'required|exists:cidades,id'
        ]);

        $medico = Medico::create($data);

        return new MedicoResource($medico);
    }

    public function medicosPorCidade($id_cidade)
    {
        $medicos = Medico::where('cidade_id', $id_cidade)->get();

        if ($medicos->isEmpty()) {
            return response()->json(['error' => 'not found'], 404);
        }

        return MedicoResource::collection($medicos);
    }
}
