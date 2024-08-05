<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
        
        // $this->validate($request,[
        //     'username' => 'required|string',
        //     'password' => 'required|string'
        // ],[
        //     'username.required' => 'Username is Required',
        //     'password.required' => 'Password id Required'
        // ]);

        // $login = [
        //     'username' => $request->username,
        //     'password' => $request->password
        // ];

        // if(Auth::attempt($login, $request->get('remember')) && Auth::user()->hasRole('admin')){
        //     return redirect()->intended(RouteServiceProvider::HOME);
        // }

        // return redirect()->route('first_page')->with('failed', 'Username/Password Salah.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
