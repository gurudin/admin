<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gurudin\Admin\Models\User;

class AuthController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    protected $redirectTo = '/admin';

    /**
     * Login view
     */
    public function loginFrom()
    {
        return view('admin::auth.login');
    }

    /**
     * (post) Login
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->post('email'), 'password' => $request->post('password')])) {
            return redirect()->route('get.group.select');
        }
    }

    /**
     * Register view
     */
    public function registerFrom()
    {
        return view('admin::auth.register');
    }

    /**
     * (post) Register
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|min:4',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        User::create($input);

        return redirect()->route('get.auth.login');
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('get.auth.login');
    }
}
