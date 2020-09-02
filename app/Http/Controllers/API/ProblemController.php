<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProblemName;
use App\Models\Problem;
use Exception;
use Illuminate\Http\JsonResponse;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $problems = Problem::orderBy('name')->get();

        return response()->json($problems, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProblemName $request
     * @return JsonResponse
     */
    public function store(ProblemName $request)
    {
        $input = $request->validated();
        $problem = Problem::create($input);

        return response()->json($problem, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Problem $problem
     * @return JsonResponse
     */
    public function show(Problem $problem)
    {
        return response()->json($problem, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProblemName $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function update(ProblemName $request, Problem $problem)
    {
        $problem->fill($request->validated());
        $problem->save();

        return response()->json($problem, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Problem $problem
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Problem $problem)
    {
        $problem->delete();

        return response()->json(['message' => 'Проблема успешно удалена'], 200);
    }
}
