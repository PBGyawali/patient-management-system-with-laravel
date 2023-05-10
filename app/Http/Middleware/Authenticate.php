<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;
use App\Models\CompanyInfo;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            return route('heading');
        }
    }



    public function handle($request, Closure $next, ...$guards)
    {
        $info=CompanyInfo::first();        
        $page=explode('.',Route::currentRouteName())[0];
       $dashboard_active=$patient_active=$appointment_active=
        $user_active=$department_active=$doctor_active=$specialization_active='inactive';
        ${$page."_active"} = 'active';
        view()->share(compact('dashboard_active','patient_active',
        'appointment_active','doctor_active','department_active','specialization_active',
      'user_active','page','info'));

        return parent::handle($request, $next);
    }
}
