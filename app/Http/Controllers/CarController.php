<?php

namespace App\Http\Controllers;

use App\Models\BookingCar;
use App\Models\Car;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class CarController extends Controller
{
    public function index()
    {
        return view('pages.car.index');
    }

    public function showData(Request $request)
    {
        $data = Car::where('id', '<>', 1)->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        $data = $row->image;
                        $x = asset('storage/car/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
                })
                ->addColumn('action', function ($row) {
                    $trans = BookingCar::where('car', $row->id)->get();
                    $count = count($trans);
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editData"><i class="fa fa-edit"></i></a>';
                    $btn .= '&nbsp;&nbsp;';
                    if ($count == 0) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                    }
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = "Aktif";
                    } else {
                        $status = "Nonaktif";
                    }
                    return $status;
                })
                ->rawColumns(['image', 'action', 'status'])
                ->make(true);
            return $allData;
        }
    }

    public function edit($id)
    {
        $data = Car::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'image' => 'image|file|max:2048',
            'number'  => 'required',
        ]);

        $dataId = $request->data_id;

        $details = [
            'number' => $request->number,
            'type' => $request->type,
            'note' => $request->note,
            'status' => $request->status
        ];

        if ($files = $request->file('image')) {
            $file = 'storage/car/' . $request->hidden_image;
            File::delete(($file));

            $destinationPath = 'storage/car/';
            $profileImage = now()->format('ymdhis') . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $details['image'] = "$profileImage";
        }

        $dataUser   =   Car::updateOrCreate(['id' => $dataId], $details);
        return response()->json($dataUser);
    }

    public function destroy($id)
    {
        $data = Car::find($id);
        $file = 'storage/car/' . $data->image;
        File::delete(($file));
        $data->delete();
        return response()->json(['success' => 'Data telah berhasil dihapus']);
    }
}
