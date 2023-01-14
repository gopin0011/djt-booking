<?php

namespace App\Http\Controllers;

use App\Models\BookingRoom;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BookingRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('status', '0')->get();
        return view('pages.booking.room', compact('rooms'));
    }

    public function showData(Request $request)
    {
        $data = BookingRoom::where('pic', Auth::user()->id)->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('time', function ($row) {
                    $time = $row->starttime . '-' . $row->endtime;
                    return $time;
                })
                ->addColumn('room', function ($row) {
                    $room = Room::find($row->room);
                    return $room->name;
                })
                ->addColumn('pic', function ($row) {
                    $pic = User::find($row->pic);
                    return $pic->name;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = "Belum Diproses";
                    } else if ($row->status == 0) {
                        $status = "Diterima";
                    } else {
                        $status = "Ditolak";
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editData"><i class="fa fa-edit"></i></a>';
                    $btn .= '&nbsp;&nbsp;';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['time', 'pic', 'status', 'action'])
                ->make(true);
            return $allData;
        }
    }

    public function today()
    {
        $date = Carbon::now()->format('l, j F Y');
        return view('pages.booking.room-today', compact('date'));
    }

    public function showDataToday(Request $request)
    {
        $data = BookingRoom::where('date', date(now()->format('Y-m-d')))->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('time', function ($row) {
                    $time = $row->starttime . '-' . $row->endtime;
                    return $time;
                })
                ->addColumn('room', function ($row) {
                    $room = Room::find($row->room);
                    return $room->name;
                })
                ->addColumn('pic', function ($row) {
                    $pic = User::find($row->pic);
                    return $pic->name;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = "Belum Diproses";
                    } else if ($row->status == 0) {
                        $status = "Diterima";
                    } else {
                        $status = "Ditolak";
                    }
                    return $status;
                })
                ->rawColumns(['time', 'pic', 'status'])
                ->make(true);
            return $allData;
        }
    }

    public function edit($id)
    {
        $data = BookingRoom::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        BookingRoom::updateOrCreate(
            ['id' => $request->data_id],
            [
                'booking_id' => 'BR-' . date(now()->format('YmdHis')),
                'room' => $request->room,
                'purpose' => $request->purpose,
                'starttime' => $request->starttime,
                'endtime' => $request->endtime,
                'date' => $request->date,
                'pic' => Auth::user()->id,
                'qty' => $request->qty,
                'note' => $request->note,
                'status' => '0'
            ]
        );
        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function destroy($id)
    {
        BookingRoom::find($id)->delete();
        return response()->json(['success' => 'Data telah berhasil dihapus']);
    }
}
