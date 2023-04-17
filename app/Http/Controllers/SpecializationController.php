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
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $actionBtn = '
                    <div class="text-center">
                        <button type="button"class="btn btn-warning btn-sm update" data-prefix="Specialization" data-status="'.$data->status.'" data-id="'.$data->specialization_id.'"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm delete_button delete" data-prefix="specialization" data-status="'.$data->status.'" id="'.$data->specialization_id.'" data-id="'.$data->specialization_id.'"><i class="fas fa-times"></i></button>
                    </div>';
                    return $actionBtn;
                })
                ->addColumn('status', function ($data) {
                    if($data->specialization_status == 'active')
                        $status = '<span  class="badge badge-success btn-sm">'.$data->specialization_status.'</span>';
                    else
                        $status = '<span  class="badge badge-danger btn-sm">'.$data->specialization_status.'</span>';
                        return $status;
                     })
                ->make(true);
        }
        $info=CompanyInfo::first();
        $page='specialization';
        return view('specialization',
        compact('info','page' )      );

    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'specialization_name' => ['required', 'string', 'max:255','unique:specializations'],
        ]);
        Specialization::create($request->all());
        return response()->json(array('error'=>'','success'=>'<div class="alert alert-success">The specialization was created!</div>'));
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
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The specialization was updated!</div>'));
    }


    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return response()->json('<div class="alert alert-success">The Specialization was deleted!</div>');
    }
}
