<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    private $fields=array();
    public $imageName='';
    public $previous_image='';
    public function index(Request $request)
    {
        //fetch all users from DB
       if ($request->ajax()) {
            $data = User::get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    // primary key of the row
                    $id=$data->id;
                    // status of the row
                    $status=$data->user_status;
                    // data to display on modal, tables
                    $prefix="user";
                    // optional button to display
                    $buttons=['delete','edit','status'];
                    $actionBtn = view('text_buttons',compact('buttons','id','status','prefix'))->render();
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
                    $status =$data->user_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('custom_badge',compact('status','class'))->render(); 
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
        }
        User::create($request->all());
        return response()->json(['response'=>__('message.create',['name'=>'user'])]);
    }

    public function show(User $user)
    {
        $info=CompanyInfo::first();
        return view('change_password2',compact('info'));
    }
    public function password(Request $request, User $user)
    {
        $this->validate($request, [
            'password' => ['required', 'string','current_password:web'
        ],
            'new_password' => ['required', 'string', 'max:255','confirmed','min:6'],
            'new_password_confirmation' => ['required', 'string', 'max:255','same:new_password'],
             ]);
        $this->fields['password']=$request->new_password;
        if(!$request->user()->is_admin() && !$user->is_same_user(auth()->id())){
            return response()->json(array('warning'=>__('message.error.auth')));
          }
        $user->update(array_merge($request->all(), $this->fields));
        return response()->json(['response'=>__('message.password')]);

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
            return response()->json(array('warning'=>__('message.error.auth')));
          }   
        // start a database transaction
        //  to ensure either all database changes are made or none of them.
        DB::beginTransaction();
        try {      
            if($request->hasFile('image')){
                // Get the original profile image file name before updating the user
                $this->previous_image = $user->getRawOriginal('profile');
                $this->fields['profile']=basename($request->file('image')->store('public/images'));            
            }      
            $user->update(array_filter(array_merge($request->all(), $this->fields)));
            if($user->wasChanged('profile')){
                $url_parts = parse_url($this->previous_image);
                //if we have image data and it is not a url
                if ($this->previous_images && !isset($url_parts['scheme']) && !isset($url_parts['host']))
                    Storage::delete('public/images/'.$this->previous_image);
                if($request->user()->is_same_user($user))
                    $this->imageName=$user->profile;
            }
            // Commit the transaction if everything is good
            DB::commit();
            return response()->json(['response'=>__('message.update',['name'=>'user']),'image'=>$this->imageName]);
        } 
        catch (\Exception $e) {
            DB::rollBack();
            //delete newly stored photo
             if($this->fields['profile'])
                Storage::delete('public/images/'.$this->fields['profile']);
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.update',['reason'=>$e->getMessage()])]);
        }

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
                return response()->json(['redirect'=>'/']);
            return redirect('/');
        }

        return response()->json(['response'=>__('message.delete',['name'=>'user'])]);
    }
}
