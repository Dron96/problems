<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolutionNameChangeRequest;
use App\Http\Requests\SolutionCreateRequest;
use App\Models\Problem;
use App\Models\Solution;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
     * @param Problem $problem
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
     * @param SolutionCreateRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function store(SolutionCreateRequest $request, Problem $problem)
    {
        $countSolution = Solution::where('problem_id', $problem->id)->count();
        if ($countSolution < 25) {
            $input = $request->validated();
            $input['creator'] = auth()->id();
            $input['problem_id'] = $problem->id;
            //dd($input);
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
     * Изменяем статус "в работе"
     *
     * @param Request $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function changeInWork(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }

        $countSolution = Solution::where('problem_id', $solution->problem_id)
            ->where('in_work', '=', true)
            ->count();
        if ($countSolution < 10) {
            if ($solution->in_work === false & (bool)$request->in_work === false) {
                return response()->json(['errors' => 'Решение не в работе'], 422);
            }
            $solution->in_work = boolval($request->in_work);
            $solution->save();

            return response()->json($solution, 200);
        } else {
            return response()->json(['errors' => 'Решений в работе слишком много, уберите хотя бы 1, чтобы продолжить'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SolutionNameChangeRequest $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function update(SolutionNameChangeRequest $request, Solution $solution)
    {
        $validator = Validator::make($solution->toArray(),
            ['problem_id' => 'exists:problems,id,deleted_at,NULL'],
            ['exists' => 'Такой проблемы не существует']);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        $solution->fill($request->validated());
        $solution->save();

        return response()->json($solution, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Solution $solution
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        $solution->delete();

        return response()->json(['message' => 'Решение успешно удалено'], 200);
    }

    /**
     * @param Request $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function changeStatus(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        if ($solution->in_work) {
            $this->validate($request, [
               'status' => [Rule::in(['В процессе', 'Выполнено', null]),],
            ], ['status.in' => 'Неверный статус']);
            $solution->status = $request->status;
            $solution->save();

            return response()->json($solution);
        } else {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
    }

    public function setDeadline(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        if ($solution->in_work) {
            $this->validate($request, ['deadline' => 'required|date|after_or_equal:'.date('Y-m-d')],
                [
                    'deadline.required' => 'Поле срок исполнения не заполнено',
                    'deadline.date' => 'Неверный формат даты',
                    'deadline.after_or_equal' => 'Срок исполнения не может быть раньше текущей даты'
                ]);
            $solution->deadline = $request->deadline;
            $solution->save();

            return response()->json($solution);
        } else {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
    }

    public function setExecutor(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        if ($solution->in_work) {
            $this->validate($request, ['executor' => 'required|exists:users,id'],
                [
                    'executor.required' => 'Поле ответственный не заполнено',
                    'executor.exists' => 'Такого пользователя не существует',
                ]);
            $solution->executor = $request->executor;
            $solution->save();

            return response()->json($solution);
        } else {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
    }
}
