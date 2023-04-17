<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;

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
        $request->authenticate();
        $request->session()->regenerate();
        //updates the password field with the new password value
       //Auth::logoutOtherDevices($request->password);

       //put the doctor status as active i.e they are available
        if(auth()->user()->is_doctor()){
            $id=auth()->id();
            $doctorlog=Doctor::where('doctor_user_id',$id)->first();
            if(!empty($doctorlog))
            $doctorlog->update(['doctor_status'=>'active']);
        }
        if ($request->ajax()) {
            return response()->json(array('response'=>RouteServiceProvider::HOME));
        }

        return redirect()->intended(RouteServiceProvider::HOME);
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
                $id=auth()->id();
                $doctorlog=Doctor::where('doctor_user_id',$id)->first();
                if(!empty($doctorlog))
                $doctorlog->update(['doctor_status'=>'inactive']);
            }

           }
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
