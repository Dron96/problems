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
        if ($problem->result === null) {
            return response()->json(['error' => 'Поле “Результат” не заполнено'], 422);
        }
        $data = ['status' => 'На проверке заказчика'];
        $correctStatuses = ['На рассмотрении', 'В работе'];
        $error = 'Действие возможно только при статусе проблемы "На рассмотрении" или "В работе"';

        return $this->updateWithStatusCheck($problem, $data, $correctStatuses, $error);
    }

    public function isIncorrectStatus($problemStatus, $correctStatuses)
    {
        if (in_array($problemStatus, $correctStatuses)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param Problem $problem
     * @param $data
     * @param array $correctStatus
     * @return Problem|\Illuminate\Http\JsonResponse
     */
    public function updateWithStatusCheck(Problem $problem, $data, $correctStatuses, $error)
    {
        if ($this->isIncorrectStatus($problem->status, $correctStatuses)) {
            return response()->json(['error' => $error], 422);
        }
        $problem->fill($data);
        $problem->save();

        return response()->json($problem, 200);
    }
}
