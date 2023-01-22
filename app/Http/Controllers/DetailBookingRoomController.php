<?php

namespace App\Http\Controllers;

use App\Models\DetailBookingRoom;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DetailBookingRoomController extends Controller
{
    public function index()
    {
    }

    public function show($id)
    {
        $datas = DetailBookingRoom::where('booking_id', $id)->get();
        return view('pages.booking.room-index', compact('datas', 'id'));
    }

    public function showDataIndex(Request $request, $id)
    {
        $data = DetailBookingRoom::where('booking_id', $id)->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    $data = Employee::where('id', $row->user)->get()[0];
                    return $data->name;
                })
                ->addColumn('email', function ($row) {
                    $data = Employee::where('id', $row->user)->get()[0];
                    return $data->email;
                })
                ->rawColumns(['user', 'email'])
                ->make(true);
            return $allData;
        }
    }

    public function detail($id)
    {
        $datas = DetailBookingRoom::where('booking_id', $id)->get();
        $employees = Employee::all();
        return view('pages.booking.room-detail', compact('datas', 'id', 'employees'));
    }

    public function store(Request $request)
    {
        DetailBookingRoom::updateOrCreate(
            ['id' => $request->data_id],
            [
                'booking_id' => $request->booking_id,
                'user' => $request->user
            ]
        );
        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function showData(Request $request, $id)
    {
        $data = DetailBookingRoom::where('booking_id', $id)->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    $data = Employee::where('id', $row->user)->get()[0];
                    return $data->name;
                })
                ->addColumn('email', function ($row) {
                    $data = Employee::where('id', $row->user)->get()[0];
                    return $data->email;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['user', 'email', 'action'])
                ->make(true);
            return $allData;
        }
    }

    public function destroy($id)
    {
        DetailBookingRoom::find($id)->delete();
        return response()->json(['success' => 'Data telah berhasil dihapus']);
    }
}
