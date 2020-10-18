<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupChangeNameRequest;
use App\Http\Requests\Group\GroupCreateRequest;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    /**
     * Получение списка всех подразделений
     *
     * @return JsonResponse
     */
    public function index()
    {
        $userGroup = auth()->user()->group;
        $groups = Group::all()->sortBy('name')->values();

        return response()->json($userGroup->merge($groups), '200');
    }

    /**
     * Создание нового подразделения
     *
     * @param GroupCreateRequest $request
     * @return JsonResponse|Response
     */
    public function store(GroupCreateRequest $request)
    {
        $input = $request->validated();
        if (Group::all()->count() >= 50) {
            return response()
                ->json(['error' => 'Подразделений слишком много, удалите хотя бы 1, чтобы продолжить'],422);
        }
        if (User::find($input['leader_id'])->group_id !== NULL) {
            return response()->json(['error' => 'Пользователь уже состоит в другом подразделении'], 422);
        }
        $group = Group::create($input);
        $leader = User::find($input['leader_id']);
        $leader->group_id = $group->id;
        $leader->save();

        return response()->json($group, 201);
    }

    /**
     * Получить подразделение
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function show(Group $group)
    {
        return response()->json($group, 200);
    }

    /**
     * Изменить название подразделения
     *
     * @param GroupChangeNameRequest $request
     * @param Group $group
     * @return JsonResponse
     */
    public function update(GroupChangeNameRequest $request, Group $group)
    {
        $group->fill($request->validated());
        $group->save();

        return response()->json($group, 200);
    }

    /**
     * Удалить подразделение
     *
     * @param Group $group
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Group $group)
    {
        $problems = $group->problems;
        if ($problems->isNotEmpty()) {
            foreach ($problems as $problem) {
                if ($problem->groups->except($group->id)->isEmpty()) {
                    if ($problem->status === 'В работе' or $problem->status === 'На проверке заказчика') {
                        return response()->json(
                            ['message' => 'Направьте проблемы этого подразделение в другое подразделение'],
                            422);
                    } elseif ($problem->status === 'Решена' or $problem->status === 'Удалена') {
                        $problem->groups()->attach(Group::all());
                    } elseif ($problem->status === 'На рассмотрении') {
                        $problem->groups()->detach();
                    }
                }
            }
        }
        $group->problems()->detach();

        $users = $group->users;
        foreach ($users as $user) {
            $user->group_id = NULL;
            $user->save();
        }

        $group->delete();

        return response()->json(['message' => 'Подразделение успешно удалено'], 200);
    }

    /**
     * Добавление пользователя в подразделение
     *
     * @param Group $group
     * @param User $user
     * @return JsonResponse
     */
    public function addUser(Group $group, User $user)
    {
        if ($user->group_id !== NULL) {
            return response()->json(['errors' => 'Пользователь уже состоит в другом подразделении'], 422);
        }
        $user->group_id = $group->id;
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Получить пользователя, который является начальником подразделения
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function getLeader(Group $group)
    {
        $leader = User::whereId($group->leader_id)->get();

        return response()->json($leader, 200);
    }

    /**
     * Получение списка пользователей, которые состоят в подразделении
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function getUsers(Group $group)
    {
        return response()->json($group->users
            ->whereNotIn('id', $group->leader_id)
            ->sortBy('father_name')
            ->sortBy('name')
            ->sortBy('surname')
            ->values(),
            200);
    }

    /**
     * Исключить пользователя из подразделения
     *
     * @param Group $group
     * @param User $user
     * @return JsonResponse
     */
    public function removeUserFromGroup(Group $group, User $user)
    {
        $usersIds = array_map('current', $group->users()->select('id')->get()->toArray());
        if (in_array($user->id, $usersIds)) {
            if ($user->id === $group->leader_id) {
                return response()->json(
                    ['errors' => 'Удаление пользователя из подразделения возможно после передачи полномочий
                    руководителя другому пользователю'],
                    422);
            }
            $user->group_id = NULL;
            $user->save();
        } else {
            return response()->json(['errors' => 'Пользователь не состоит в этом подразделении'], 422);
        }

        return response()->json(['message' => 'Пользователь успешно удален из подразделения'], 200);
    }

    /**
     * Сменить пользователя, который является начальником подразделения
     *
     * @param Group $group
     * @param User $user
     * @return JsonResponse
     */
    public function changeLeader(Group $group, User $user)
    {
        if ($user->group_id !== $group->id) {
            return response()->json(['errors' => 'Пользователь не состоит в этом подразделении'], 422);
        }
        if ($user->id === $group->leader_id) {
            return response()
                ->json(['errors' => 'Пользователь уже является руководителем этого подразделения'], 422);
        }
        $user->group_id = $group->id;
        $user->save();
        $group->leader_id = $user->id;
        $group->save();

        return response()->json($group, 200);
    }
}
