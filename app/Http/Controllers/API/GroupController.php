<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupCreateRequest;
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
        $groups = Group::all()->sortBy('name');

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

        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addUser(Group $group, User $user)
    {
        if ($user->group_id !== NULL) {
            return response()->json(['errors' => 'Пользователь уже состоит в другом подразделении'], 422);
        }
        $user->group_id = $group->id;
        $user->save();

        return response()->json($user, 200);
    }

    public function getLeader(Group $group)
    {
        $leader = User::whereId($group->leader_id)->get();

        return response()->json($leader, 200);
    }

    public function getUsers(Group $group)
    {
        return response()->json($group->users->sortBy('father_name')->sortBy('name')->sortBy('surname'), 200);
    }
}
