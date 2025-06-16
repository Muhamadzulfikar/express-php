<?php

namespace Controllers;

use Illuminate\Http\Request;
use Models\User;

class UserController
{
    public function index()
    {
        $users = User::all();
        return view('users/index.php', ['users' => $users]);
    }

    public function create()
    {
        return view('users/create.php');
    }

    public function store()
    {
        $request = Request::capture();
        
        $data = validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        User::create($data);
        header('Location: /users');
        exit;
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users/show.php', ['user' => $user]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        header('Location: /users');
        exit;
    }
}
