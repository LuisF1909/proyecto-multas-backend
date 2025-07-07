<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Multa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para obtener el usuario autenticado si es necesario
use Illuminate\Support\Facades\Validator; // Para validación

class MultaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Retorna un JSON con las multas.
     */
    public function index(Request $request)
    {
        // Opcionalmente, podrías filtrar por departamento si el usuario tiene un rol específico
        // Por ahora, listaremos todas las multas con su departamento.
        // La paginación es una buena práctica para listas largas.
        $multas = Multa::with('department:id,name') // Carga selectiva del departamento
                        ->latest() // Ordena por fecha de creación descendente
                        ->paginate(15); // Muestra 15 por página

        return response()->json($multas);
    }

    /**
     * Store a newly created resource in storage.
     * Crea una nueva multa.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id', // Asegura que el department_id exista en la tabla departments
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // Error de validación
        }

        $multa = Multa::create($validator->validated());

        // El MultaObserver se encargará de crear las notificaciones automáticamente.

        // Retornamos la multa creada, incluyendo la información del departamento (opcional).
        return response()->json($multa->load('department:id,name'), 201); // 201 Created
    }

    /**
     * Display the specified resource.
     * Muestra una multa específica.
     */
    public function show(Multa $multa)
    {
        // Carga la relación con el departamento para mostrar su nombre.
        return response()->json($multa->load('department:id,name'));
    }

    // Los métodos update y destroy se pueden implementar si se necesitan en el futuro.
    // public function update(Request $request, Multa $multa) { ... }
    // public function destroy(Multa $multa) { ... }
}
