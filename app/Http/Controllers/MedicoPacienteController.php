<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Models\MedicoPaciente;
use App\Http\Resources\MedicoResource;
use App\Http\Resources\PacienteResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicoPacienteController extends Controller
{
    public function relacionarPaciente(Request $request, $id_medico)
    {
        $data = $request->validate([
            'paciente_id' => 'required|exists:paciente,id',
        ]);

        try {
            $medico = Medico::findOrFail($id_medico);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        $paciente = Paciente::findOrFail($data['paciente_id']);

        MedicoPaciente::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
        ]);

        return [
            'medico' => new MedicoResource($medico),
            'paciente' => new PacienteResource($paciente),
        ];
    }

    public function listarPacientesDoMedico($id_medico)
    {
        try {
            $medico = Medico::findOrFail($id_medico);
            $pacientes = $medico->pacientes;

            return PacienteResource::collection($pacientes);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
    }
}
