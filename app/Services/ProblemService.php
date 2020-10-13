<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Like;
use App\Models\Problem;
use App\Models\Solution;
use App\User;

class ProblemService
{
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
        if ($solution->status !== 'Выполнено' and $problem->status === 'В работе') {
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

    public function store($input)
    {
        if (auth()->user()->group_id === NULL) {
            return response()->json(['error' => 'Вы не состоите ни в одном из подразделений'], 422);
        }
        if (Problem::all()->count() >= 10000 ) {
            return response()->json(['error' => 'Список проблем переполнен'], 422);
        }
        $input['creator_id'] = auth()->id();
        $problem = Problem::create($input);
        Like::create([
            'user_id' => auth()->id(),
            'problem_id' => $problem->id,
        ]);
        Solution::create([
            'name' => '',
            'problem_id' => $problem->id,
        ]);

        return response()->json($problem, 201);
    }

    public function delete(Problem $problem)
    {
        $correctStatuses = ['Решена', 'Удалена'];
        if (!$this->isIncorrectStatus($problem->status, $correctStatuses)) {
            return response()->json(['error' => 'Действие возможно при любом статусе проблемы, кроме “Удалена” и “Решена”'], 422);
        }
        $problem->status = 'Удалена';
        $problem->save();

        return response()->json([$problem, 'message' => 'Проблема успешно удалена'], 200);
    }

    public function likeProblem(Problem $problem)
    {
        $userIds = array_map('current', $problem->likes()->select('user_id')->get()->toArray());
        if (in_array(auth()->id(), $userIds)) {
            Like::where('user_id', auth()->id())->delete();
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'problem_id' => $problem->id,
            ]);
        }

        return response()->json(['message' => 'Успешно'], 200);
    }

    public function sendToGroup(Problem $problem, $groupIds)
    {
        $groups = Group::find($groupIds);
        if ($groupIds == NULL) {
            return response()->json(['error' => 'Выберите хотя бы одно подразделение для отправки проблемы']);
        }
        if (sizeof($groups) !== sizeof($groupIds)) {
            return response()->json(['error' => 'Выбрано не существующее подразделение'], 422);
        }
        $problem->groups()->detach();
        $problem->groups()->attach($groups);
        if ($problem->status === 'На рассмотрении') {
            $problem->status = 'В работе';
            $problem->save();
        }

        return response()->json($problem->groups, 200);
    }
}
