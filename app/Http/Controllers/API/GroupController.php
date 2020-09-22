<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupChangeNameRequest;
use App\Http\Requests\Group\GroupChangeShortNameRequest;
use App\Http\Requests\Group\GroupCreateRequest;
use App\Models\Group;
use App\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $groups = Group::all()->sortBy('name')->values();

        return response()->json($groups, '200');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(GroupCreateRequest $request)
    {
        $input = $request->validated();
        if (Group::all()->count() >= 50) {
            return response()->json(['error' => 'Подразделений слишком много, удалите хотя бы 1, чтобы продолжить'], 422);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Group $group)
    {
        return response()->json($group, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GroupChangeNameRequest $request, Group $group)
    {
        $group->fill($request->validated());
        $group->save();

        return response()->json($group, 200);
    }

    /**
     * @param GroupChangeShortNameRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateShortName(GroupChangeShortNameRequest $request, Group $group)
    {
        $group->fill($request->validated());
        $group->save();

        return response()->json($group, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Group $group)
    {
        $users = $group->users;
        foreach ($users as $user) {
            $user->group_id = NULL;
            $user->save();
        }
        $group->delete();

        return response()->json(['message' => 'Подразделение успешно удалено'], 200);
    }

    /**
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
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
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeader(Group $group)
    {
        $leader = User::whereId($group->leader_id)->get();

        return response()->json($leader, 200);
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Group $group)
    {
        return response()->json($group->users->whereNotIn('id', $group->leader_id)->sortBy('father_name')->sortBy('name')->sortBy('surname')->values(), 200);
    }

    /**
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeUserFromGroup(Group $group, User $user)
    {
        $usersIds = array_map('current', $group->users()->select('id')->get()->toArray());
        if (in_array($user->id, $usersIds)) {
            if ($user->id === $group->leader_id) {
                return response()->json(['errors' => 'Удаление пользователя из подразделения возможно после передачи полномочий руководителя другому пользователю'], 422);
            }
            $user->group_id = NULL;
            $user->save();
        } else {
            return response()->json(['errors' => 'Пользователь не состоит в этом подразделении'], 422);
        }

        return response()->json(['message' => 'Пользователь успешно удален из подразделения'], 200);
    }

    /**
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeLeader(Group $group, User $user)
    {
        if ($user->group_id !== $group->id) {
            return response()->json(['errors' => 'Пользователь не состоит в этом подразделении'], 422);
        }
        if ($user->id === $group->leader_id) {
            return response()->json(['errors' => 'Пользователь уже является руководителем этого подразделения'], 422);
        }
        $user->group_id = $group->id;
        $user->save();
        $group->leader_id = $user->id;
        $group->save();

        return response()->json($group, 200);
    }
}
