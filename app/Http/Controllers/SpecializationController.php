<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\Specialization;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $companyInfo=array();
    public $query='';


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Specialization::get();
            return DataTables::of($data)             
                ->addColumn('action', function($data){
                    $element='specialization';
                    $id=$data->getKey();
                    $actionBtn = view('text_buttons',compact('id','element'))->render();            
                    return $actionBtn;
                })
                ->editColumn('specialization_status', function ($data) {               
                    $status =$data->specialization_status;
                    $class=$status=='active'?'success':'danger';
                    //render status with css from view
                    return view('custom_badge',compact('status','class'))->render();               
            })                
                ->make(true);
        }
        $info=CompanyInfo::first();
        $page='specialization';
        return view('specializations',
        compact('info','page' ));

    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'specialization_name' => ['required', 'string', 'max:255','unique:specializations'],
        ]);
        Specialization::create($request->all());
        return response()->json(array('response'=>__('message.create',['name'=>'specialization'])));
    }


    public function edit(Specialization $specialization)
    {

        return response()->json($specialization);
    }


    public function update(Request $request, Specialization $specialization)
    {
        $this->validate($request, [
            'specialization_name' => ['required', 'string', 'max:255',Rule::unique('specializations')->ignore($specialization)]]);
        $specialization->update($request->all());
        return response()->json(array('response'=>__('message.update',['name'=>'specialization'])));

    }


    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return response()->json(array('response'=>__('message.delete',['name'=>'specialization'])));
    }
}
