<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(User::all());
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
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];

        $messages = [
            'required' => 'El campo :attribute es obligatorio',
            'email.required' => 'El campo correo no tiene el formato adecuado',
            'required' => 'La contraseña es campo obligatorio'
        ];

        $validatedData = $request->validate($rules, $messages);
        $user = User::create($validatedData);

        $validatedData['password'] = bcrypt($validatedData['password']);
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
        ];

        $messages = [
            'required' => 'El campo :attribute es obligatorio',
            'email.required' => 'El campo correo no tiene el formato adecuado',
            'required' => 'La contraseña es campo obligatorio'
        ];

        $validatedData = $request->validate($rules, $messages);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->fill($validatedData);

        if (!$user->isDirty()) {
            return  $this->errorResponse('please specify at least one different value', 422);
        }
        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }
}
