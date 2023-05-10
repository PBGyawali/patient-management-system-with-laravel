<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('welcome');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \App\Http\Requests\Auth\LoginRequest
     * //@return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        // Attempt to authenticate the user
         if ($request->authenticate()) {
            // If authentication is successful, get the authenticated user
            $user = Auth::user();

            // Check if the user is active
            if ($user->is_active()) {
                // If the user is active, regenerate the session and redirect to the home page
                $request->session()->regenerate();
                if(auth()->user()->is_doctor()){                    
                    Doctor::where('doctor_user_id',auth()->id())
                    ->update(['doctor_status'=>'active']);                    
                }
                if ($request->ajax()) {
                    return response()->json(['redirect'=>RouteServiceProvider::HOME]);
                }
                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                // If the user is inactive, log them out and show an error message
                Auth::logout();
                throw ValidationException::withMessages(['inactive' =>__('auth.inactive')]);
            }
        } else {
            // If authentication fails, show an error message
            throw ValidationException::withMessages(['email'=> __('auth.failed')]);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        if (auth()->user()){
            if(auth()->user()->is_doctor()){
                Doctor::where('doctor_user_id',auth()->id())
                ->update(['doctor_status'=>'inactive']);
            }

        }
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
