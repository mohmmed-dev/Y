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

class UserController extends Controller
{

    protected $userId ;
    public function __construct()
    {
        $this->userId = Auth::user();
    }
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
        $users = UserResource::collection(User::paginate($limit));
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // FacadesGate::authorize('create',User::class);
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = new UserResource(User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]
        ));
        return $user->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::findOrFail($user->id);
        // FacadesGate::authorize('update',$user);
        $user = new UserResource($user);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user = User::findOrFail($user->id);
        $id = auth('api')->user();
        FacadesGate::authorize('update', [$user,$id]);
        $user = new UserResource($user);
        $user->update($request->all());
        return $user->response()->setStatusCode(200,'User Update Scccfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $IDuser = new UserResource(User::findOrFail($user->id));
        $id = auth('api')->user();

        FacadesGate::authorize('update', [$user,$id]);
        $IDuser->delete();
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
        $user = User::findOrFail($user->id);
        $group = Group::findOrFail($post->group_id);
        abort_if(!($user->id == $group->user_id && $group->isMember($user)),403,'You Do Not Permission To Preform This action.');
        $user->like($post);
        return 200;
    }
    public function unLike(User $user ,Post $post) {
        $user = User::findOrFail($user->id);
        $group = Group::findOrFail($post->group_id);
        abort_if(!($user->id == $group->user_id && $group->isMember($user)),403,'You Do Not Permission To Preform This action.');
        $user->unLike($post);
        return 200;
    }
}
