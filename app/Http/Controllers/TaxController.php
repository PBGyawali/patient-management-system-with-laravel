<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    public $companyInfo=[];

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Tax::get();
            return DataTables::of($data)                
                ->addColumn('action', function($data){
                    $element='tax';
                    $id=$data->tax_id;
                    $actionBtn = view('text_buttons',compact('id','element'))->render();            
                    return $actionBtn;
                })
                ->editColumn('tax_status', function ($data) {
                    $status =$data->tax_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('custom_badge',compact('status','class'))->render(); 
                })             
                ->make(true);
        }

        $info=CompanyInfo::first();
        $page='tax';
        return view('taxes',
        compact('info','page' ));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'tax_name' => ['required', 'max:255','unique:taxes'],
            'tax_percentage' => ['required','numeric','between:0,99.99'],
        ]);
        Tax::create($request->all());
        return response()->json(['response'=>__('message.create',['name'=>'tax'])]);
    }

    public function edit(Tax $tax)
    {
        return response()->json($tax);
    }

    public function update(Request $request, Tax $tax)
    {
        if (!$request->hasAny('tax_status','status')){
        $this->validate($request, [
            'tax_name' => ['required','max:255',Rule::unique('taxes')->ignore($tax)],
            'tax_percentage' => ['required','numeric','between:0,99.99'],
            ]);
        }
        $tax->update($request->all());
        return response()->json(['response'=>__('message.update',['name'=>'tax'])]);
    }

  public function destroy(Request $request,Tax $tax)
    {
        // start a database transaction
        //  to ensure either all database changes are made or none of them.
        DB::beginTransaction();

        try {            
            $tax->delete();
            // Commit the transaction if everything is good
            DB::commit();
            return response()->json(['response'=>__('message.delete',['name'=>'tax'])]);
        } catch (\Exception $e) {
            DB::rollBack();
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.delete',['reason'=>$e->getMessage()])]);
        }
    }
}
