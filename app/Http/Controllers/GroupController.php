<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Http\Resources\GroupResource;
use Illuminate\Support\Facades\Gate;
use App\Policies\GroupPolicy;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GroupController extends Controller implements HasMiddleware
{

    public static function middleware() {
        return [
            new Middleware('auth:api', except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit =  $request->input('limit') <= 25 ? $request->input('limit') : 25;
        $groups = GroupResource::collection(Group::where('public' , 1)->withCount('posts','members')->paginate($limit));
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
            'description' => 'required',
            'image' => ['nullable','image'],
        ]);

        if($request->hasFile('image')) {
            $pathName = str()->random(25) . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('groups',$pathName);
            $data['image'] = 'groups/' . $pathName;
        } else {
            $data['image'] = null;
        }

        $data['public'] = $request->has('public') ? +$request->public : 1;

        $group = Group::create([
            'name' => $data['name'],
            'user_id' => $data['userId'],
            'description' => $data['description'],
            'image' => $data['image'],
            'public' => $data['public'],
        ]);

        $group->loadCount('posts','members');
        $group = new GroupResource($group);
        return $group->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        if(!$group->public) {
            Gate::authorize('update-group',$group);
        }
        $group = new GroupResource($group->loadCount('posts','members'));
        return $group;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        Gate::authorize('update', $group);
        if($request->hasFile('image')) {
            $pathName = str()->random(25) . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('groups',$pathName);
            $image = 'groups/' . $pathName;
            $group->update($request->except('image') + ['image' => $image]);
        } else {
            $group->update($request->except('image'));
        }
        $group = new GroupResource($group);
        $group->loadCount('posts','members');
        return $group->response()->setStatusCode(200,'Group Update Scccfully');
    }
}
