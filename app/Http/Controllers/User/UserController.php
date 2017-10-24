<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('province')->get();
        $collection = $this->paginate($users);
        return $this->successResponse($collection, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:2',
            'telephone' => 'required|string',
            'postal_code' => 'required|string',
            'salary' => 'required|string',
            'province_id' => 'required|string'
        ];

        $this->validate($request, $rules);

        $user = User::create($request->all());

        return $this->showOne($user, 201);
    }
    public function update(Request $request, User $user) {
        $rules = [
            'name' => 'string|min:2',
            'telephone' => 'string',
            'postal_code' => 'string',
            'salary' => 'string'
        ];

        $this->validate($request, $rules);

        if($request->has('name')) {
            $user->name = $request->name;
        }
        if($request->has('province_id')) {
            $user->province_id = $request->province_id;
        }
        if($request->has('telephone')) {
            $user->telephone = $request->telephone;
        }
        if($request->has('postal_code')) {
            $user->postal_code = $request->postal_code;
        }
        if($request->has('salary')) {
            $user->salary = $request->salary;
        }
        $user->save();
        return $this->showOne($user);
    }
    public function destroy(User $user) {
        $user->delete();
        return $this->showOne($user);
    }
}
