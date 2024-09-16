<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Http\Resources\GroupResource;
use Illuminate\Support\Facades\Gate;
use App\Policies\GroupPolicy;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GroupController extends Controller
{

    public static function middleware() {
        return [
            'auth' , new Middleware('api', except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit =  $request->input('limit') <= 25 ? $request->input('limit') : 25;
        $groups = GroupResource::collection(Group::paginate($limit));
        return $groups;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'userId'=> 'required',
            'description' => 'required'
        ]);
        $group = new GroupResource(Group::create([
            'name' => $data['name'],
            'user_id' => $data['userId'],
            'description' => $data['description']
        ]));
        return $group->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $id = auth('api')->user();
        $group = Group::findOrFail($group->id);
        $group = new GroupResource($group);
        return $group;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $id = auth('api')->user();
        $group = Group::findOrFail($group->id);
        Gate::authorize('update', [$group,$id]);
        $group = new GroupResource($group);
        $group->update($request->all());
        return $group->response()->setStatusCode(200,'Group Update Scccfully');
    }
}
