<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Http\Resources\PacienteResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PacienteController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'required|string|max:20',
            'celular' => 'required|string|max:20',
        ]);

        $paciente = Paciente::create($data);

        return new PacienteResource($paciente);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'required|string|max:20',
            'celular' => 'required|string|max:20',
        ]);


        try {
            $paciente = Paciente::findOrFail($id);
            $paciente->update($data);
            return new PacienteResource($paciente);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Paciente not found'], 404);
        }
    }
}
