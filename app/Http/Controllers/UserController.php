<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware() {
        return [
            new Middleware('auth:api', except: ['index','show','store'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit') <= 25 ? $request->input('limit') : 25;
        $users = UserResource::collection(User::withCount(['groups','members'])->paginate($limit));
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'image' => 'image',
        ]);
        // Image Handle And Store
        $user = User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]
        );
        $user->loadCount(['groups','members']);
        $user = new UserResource($user);
        return $user->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->loadCount(['groups','members']);
        $user = new UserResource($user);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        FacadesGate::authorize('update', $user);
        $user = new UserResource($user);
        $user->update($request->all());
        $user->loadCount(['groups','members']);
        return $user->response()->setStatusCode(200,'User Update Scccfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        FacadesGate::authorize('delete', $user);
        $user->delete();
        return response()->json(['message' => 'User Delete Scccfully'],204);
    }

    public function addMember(User $user ,Group $group) {
        $user->addMember($group);
        return 200;
    }

    public function deleteMember(User $user ,Group $group) {
        $user->deleteMember($group);
        return 200;
    }

    public function like(User $user ,Post $post) {
        $group = $post->group;
        FacadesGate::authorize('update-group',$group);
        $user->like($post);
        return 200;
    }

    public function unLike(User $user ,Post $post) {
        $group = $post->group;
        FacadesGate::authorize('update-group',$group);
        $user->unLike($post);
        return 200;
    }
}
