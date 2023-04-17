<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    var $fields=array();
    public $companyInfo=array();
    public $query='';
    public $imageName='';

    public function index(Request $request)
    {
        //fetch all users from DB
       if ($request->ajax()) {
            $data = User::get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '
                    <div class="text-center">
                        <button type="button"class="btn btn-warning btn-sm update" data-prefix="User" data-id="'.$data->id.'">
                        <i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm delete" data-id="'.$data->id.'">
                        <i class="fas fa-times"></i></button>
                        <button type="button"  data-prefix="user" class="btn btn-success btn-sm status p-0" id="'.$data->id.'"  data-status="'.$data->user_status.'" data-id="'.$data->id.'"><i class="far fa-calendar-check  fa-2x"></i></button>
                    </div>';
                    return $actionBtn;
                })
                ->editColumn('created_at', function ($data) {
                    if ($data->created_at!=null )
                        return $data->created_at->format('Y/m/d');
                 })
                ->editColumn('profile', function ($data) {
                    $image = 'No image';
                    if($data->profile != '')
                        $image= '<img src="'.$data->profile.'" class="img img-thumbnail " width="75" >';
                        return $image;
                     })
                ->editColumn('user_status', function ($data) {
                    if($data->is_active())
                        $status = '<span  class="badge badge-success btn-sm">'.$data->user_status.'</span>';
                    else
                        $status = '<span  class="badge badge-danger btn-sm">'.$data->user_status.'</span>';
                        return $status;
                     })
                ->make(true);
        }
        $info=CompanyInfo::first();
        $page='user';
        return view('user',compact('info','page' ) );
    }

    public function create()
    {
        $user=auth()->user();
        $info=CompanyInfo::first();
        return view('profile',compact('info','user'));
    }

    public function store(Request $request)
    {
       $this->validate($request, [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password'=>['required', 'string'],
        ]);
        if($request->hasFile('image')){
            $this->fields['profile']=basename($request->file('image')->store('public/images'));
            $this->imageName=config('app.storage_url').$this->fields['profile'];
        }
        User::create(array_merge($request->all(), $this->fields));
       return response()->json(array('response'=>'<div class="alert alert-success">The user data was created!</div>','image'=>$this->imageName));
    }

    public function show(User $user)
    {
        $info=CompanyInfo::first();
        return view('change_password',compact('info'));
    }
    public function password(Request $request, User $user)
    {
        $this->validate($request, [
            'password' => ['required', 'string','current_password:web'
        ],
            'new_password' => ['required', 'string', 'max:255','confirmed','min:6'],
            'new_password_confirmation' => ['required', 'string', 'max:255','same:new_password'],
             ]);
        $this->fields['id']=auth()->user()->id;
        $this->fields['password']=$request->new_password;
        if(!$request->user()->is_admin() && !$user->is_same_user(auth()->id())){
            return response()->json(array('response'=>'<div class="alert alert-warning">This request is unauthorized!</div>'));
          }
        $user->update(array_merge($request->all(), $this->fields));

        return response()->json(array('response'=>'<div class="alert alert-success">The user password was updated!Please Re-login</div>','error'=>''));
    }
    public function update(Request $request, User $user)
    {

        if(!$request->hasAny('user_status')){
            $this->validate($request, [
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)
            ],
                ]);

        }
        if(!$request->user()->is_admin() && !$user->is_same_user(auth()->id())){
            return response()->json(array('response'=>'<div class="alert alert-warning">This request is unauthorized!</div>'));
          }
        if($request->hasFile('image')){
            $this->fields['profile']=basename($request->file('image')->store('public/images'));
            $this->imageName=config('app.storage_url').$this->fields['profile'];
        }
       $user->update(array_filter(array_merge($request->all(), $this->fields)));

        return response()->json(array('response'=>'<div class="alert alert-success">The user data was updated!</div>','image'=>$this->imageName));
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }
    public function destroy(User $user)
    {
        $user->delete();
        if($user->is_doctor()){
            $user->doctor->delete();
        }
        if($user->is_same_user(auth()->id())){
            auth()->guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            if(request()->ajax())
                return response()->json(array('redirect'=>'/'));
            return redirect('/');
        }

        return response()->json('<div class="alert alert-success">The user was deleted!</div>');
    }
}
