<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $logo=CompanyInfo::first()->facility_alternate_logo;
        return view('auth.confirm-password',compact('logo'));
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'secret_password' => ['required'],
            'password' => ['required','current_password:web'],
        ]);
        $secret_password =CompanyInfo::first()->secret_password;

        if (!Hash::check($request->secret_password, $secret_password)) {
           throw ValidationException::withMessages([
                'secret_password' => __('auth.password'),
            ]);
        }



       $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);

    }
}
