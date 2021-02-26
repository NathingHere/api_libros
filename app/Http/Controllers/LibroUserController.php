<?php

namespace App\Http\Controllers;

use App\Libro;
use App\User;
use App\LibroUser;
use Illuminate\Http\Request;

class LibroUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Libro $libro)
    {
        $users = $libro->users;
        return $this->showAll($users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LibroUser  $libroUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Libro $libro)
    {
        $libro->users()->syncWithoutDetaching([$user->id]);
        return $this->showAll($libro->users);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LibroUser  $libroUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Libro $libro)
    {
        if (!$libro->users()->find($user->id)) {
            return $this->errorResponse('Este usuario no tomo prestado el libro', 404);
        }

        $libro->users()->detach($user->id);

        return $this->showAll($libro->users);
    }
}
