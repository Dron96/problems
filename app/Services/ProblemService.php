<?php

namespace App\Services;

use App\Models\Problem;
use App\Models\Solution;
use App\User;

class ProblemService
{
    public function isLikedProblem($id)
    {
        $problem = Problem::where('id', $id)->with('likes')->first()->toArray();
        $userIds = array_column($problem["likes"], 'user_id');

        return in_array(auth()->id(), $userIds);
    }

    public function update(Problem $problem, $data)
    {
        $problem->fill($data);
        $problem->save();

        return $problem;
    }

    public function sendForConfirmation(Problem $problem, $userId, Solution $solution)
    {
        $user = User::find($userId);
        if ($user === NULL) {
            return response()->json(['error' => 'Пользователя, сообщившего о проблеме, больше не существует в системе. Обратитесь к администратору.'], 404);
        }
        if ($solution->status !== 'Выполнено') {
            return response()->json(['error' => 'Решение не выполнено'], 422);
        }
        if ($solution->result === NULL) {
            return response()->json(['error' => 'Поле “Результат” не заполнено'], 422);
        }
        $problem->status = 'На проверке заказчика';
        $problem->save();

        return response()->json($problem, 200);
    }

    public function rejectSolution(Problem $problem)
    {
        $problem->status = 'На рассмотрении';
        $problem->save();

        return response()->json($problem, 200);
    }

    public function confirmSolution(Problem $problem)
    {
        $problem->status = 'Решена';
        $problem->save();

        return response()->json($problem, 200);
    }
}
