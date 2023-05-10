<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public $companyInfo=[];

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Service::get();
            return DataTables::of($data)                
                ->addColumn('action', function($data){
                    $element='service';
                    $id=$data->service_id;
                    $actionBtn = view('text_buttons',compact('id','element'))->render();            
                    return $actionBtn;
                })
                ->editColumn('service_status', function ($data) {
                    $status =$data->service_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('custom_badge',compact('status','class'))->render(); 
                })             
                ->make(true);
        }

        $info=CompanyInfo::first();
        $page='service';
        return view('services',compact('info','page' ));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'service_name' => ['required', 'max:255','unique:services'],
            'service_price' => ['required','numeric','min:0'],
        ]);
        Service::create($request->all());
        return response()->json(['response'=>__('message.create',['name'=>'Service'])]);
    }

    public function edit(Service $service)
    {
        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        if (!$request->hasAny('service_status','status')){
        $this->validate($request, [
            'service_name' => ['required','max:255',Rule::unique('Services')->ignore($service)],
            'service_price' => ['required','numeric','min:0'],
            ]);
        }
        $service->update($request->all());
        return response()->json(['response'=>__('message.update',['name'=>'Service'])]);
    }

  public function destroy(Request $request,Service $service)
    {
        // start a database transaction
        //  to ensure either all database changes are made or none of them.
        DB::beginTransaction();

        try {            
            $service->delete();
            // Commit the transaction if everything is good
            DB::commit();
            return response()->json(['response'=>__('message.delete',['name'=>'Service'])]);
        } catch (\Exception $e) {
            DB::rollBack();
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.delete',['reason'=>$e->getMessage()])]);
        }
    }
}
