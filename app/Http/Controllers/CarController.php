<?php

namespace App\Http\Controllers;

use App\Models\BookingCar;
use App\Models\Car;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CarController extends Controller
{
    public function index(){
        return view('pages.car.index');
    }

    public function showData(Request $request){
        $data = Car::where('id','<>',1)->get();
        if($request->ajax()){
            $allData = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $trans = BookingCar::where('car',$row->id)->get();
                $count = count($trans);
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' .$row->id. '" data-original-title="Edit" class="edit btn btn-primary btn-sm editData"><i class="fa fa-edit"></i></a>';
                $btn .= '&nbsp;&nbsp;';
                if($count==0)
                {
                    $btn.= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' .$row->id. '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                }
                return $btn;
            })
            ->addColumn('status',function($row){
                if($row->status ==0)
                {
                    $status = "Aktif";
                }else{
                    $status = "Nonaktif";
                }
                return $status;
            })
            ->rawColumns(['action','status'])
            ->make(true);
            return $allData;
        }
    }

    public function edit($id)
    {
        $data = Car::find($id);
        return response()->json($data);
    }

    public function store(Request $request){

        Car::updateOrCreate(
            ['id'=>$request->data_id],
            ['number'=>$request->number,
            'type'=>$request->type,
            'note'=>$request->note,
            'status'=>$request->status
            ]
        );
        return response()->json(['success'=>'Data telah berhasil disimpan']);
    }

    public function destroy($id){
        Car::find($id)->delete();
        return response()->json(['success'=>'Data telah berhasil dihapus']);
    }
}
