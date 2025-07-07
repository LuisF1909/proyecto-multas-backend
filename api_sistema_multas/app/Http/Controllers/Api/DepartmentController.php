<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtiene todos los departamentos, solo las columnas id y name, y los ordena por nombre.
        $departments = Department::orderBy('name')->get(['id', 'name']);
        return response()->json($departments);
    }

    // Puedes dejar los otros métodos vacíos por ahora.
}
