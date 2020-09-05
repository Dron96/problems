<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolutionRequest;
use App\Models\Problem;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\Types\Integer;

class SolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Problem $problem
     * @return JsonResponse|Response
     */
    public function index(Problem $problem)
    {
        $solutions1 = Solution::where('problem_id', $problem->id)
            ->where('in_work', true)
            ->orderBy('name')
            ->get();
        $solutions2 = Solution::where('problem_id', $problem->id)
            ->where('in_work', false)
            ->latest()
            ->get();
        $solutions = $solutions1->merge($solutions2);

        return response()->json($solutions, 200);
    }

    /**
     * Показывает решения в работе для данной проблемы
     *
     * @param Solution $solution
     * @return JsonResponse
     */
    public function showInWork(Problem $problem)
    {
        $solutions = Solution::where('problem_id', $problem->id)
            ->where('in_work', true)
            ->orderBy('name')
            ->get();
        return response()->json($solutions, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SolutionRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function store(SolutionRequest $request, Problem $problem)
    {
        $countSolution = Solution::where('problem_id', $problem->id)->count();
        if ($countSolution < 25) {
            $input = $request->validated();
            $input['user_id'] = auth()->id();
            $input['problem_id'] = $problem->id;
            $solution = Solution::create($input);

            return response()->json($solution, 201);
        } else {
            return response()->json(['errors' => 'Решений слишком много, удалите хотя бы 1, чтобы продолжить'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Solution $solution
     * @return JsonResponse
     */
    public function show(Solution $solution)
    {
        return response()->json($solution, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
