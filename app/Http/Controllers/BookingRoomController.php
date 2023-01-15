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
                ->addColumn('qty', function ($row) {
                    $id = $row->booking_id;
                    // $qty = count($id);
                    $qty = 0;
                    return $qty;
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
                    if($row->status == '1')
                    {
                        $btn = 'Disetujui';
                    }else{
                        $btn = 'Ditolak';
                    }

                    if($row->status == '0')
                    {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editData"><i class="fa fa-edit"></i></a>';
                        $btn .= '&nbsp;&nbsp;';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['time', 'pic', 'qty', 'status', 'action'])
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
                ->addColumn('qty', function ($row) {
                    $id = $row->booking_id;
                    // $qty = count($id);
                    $qty = 0;
                    return $qty;
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
                ->rawColumns(['time', 'pic', 'qty', 'status'])
                ->make(true);
            return $allData;
        }
    }

    public function approve()
    {
        $rooms = Room::where('status', '0')->get();
        return view('pages.booking.room-approve', compact('rooms'));
    }

    public function approveData(Request $request)
    {
        $data = BookingRoom::query();
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
                ->addColumn('qty', function ($row) {
                    $id = $row->booking_id;
                    // $qty = count($id);
                    $qty = 0;
                    return $qty;
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
                ->rawColumns(['time', 'pic', 'qty', 'status', 'action'])
                ->make(true);
            return $allData;
        }
    }

    public function all()
    {
        return view('pages.booking.room-all');
    }

    public function showDataAll(Request $request)
    {
        $data = BookingRoom::where([['status','<>','2'],['date','>=',date(now()->format('Y-m-d'))],['starttime','>=',date(now()->format('H:i:s'))]])->get();
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
                ->addColumn('qty', function ($row) {
                    $id = $row->booking_id;
                    // $qty = count($id);
                    $qty = 0;
                    return $qty;
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
                ->rawColumns(['time', 'pic', 'qty', 'status', 'action'])
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
        // error_log($request->data_id);
        if ($request->data_id == '') {
            $cek = BookingRoom::where([['room', $request->room], ['date', $request->date], ['starttime', '>=', $request->starttime], ['starttime', '<=', $request->endtime]])->orWhere([['room', $request->room], ['date', $request->date], ['endtime', '>=', $request->starttime], ['endtime', '<=', $request->endtime]])->orWhere([['room', $request->room], ['date', $request->date], ['starttime', '<=', $request->starttime], ['endtime', '>=', $request->endtime]])->get();

            if (count($cek) != 0) {
                return response()->json(['failed' => 'Ruangan tidak tersedia di waktu tersebut']);
            } else {
                // error_log($request->data_id);
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
                        // 'qty' => $request->qty,
                        'note' => $request->note,
                        'status' => '0'
                    ]
                );
            }
        } else {
            $cek = BookingRoom::where([['room', $request->room], ['date', $request->date], ['starttime', '>=', $request->starttime], ['starttime', '<=', $request->endtime]])->orWhere([['room', $request->room], ['date', $request->date], ['endtime', '>=', $request->starttime], ['endtime', '<=', $request->endtime]])->orWhere([['room', $request->room], ['date', $request->date], ['starttime', '<=', $request->starttime], ['endtime', '>=', $request->endtime]])->get();

            if (count($cek) != 0) {
                $data = BookingRoom::find($request->data_id);
                $data->update(
                    [
                        'purpose' => $request->purpose,
                        'note' => $request->note,
                        'status' => $request->status
                    ]
                );
            } else {
                $data = BookingRoom::find($request->data_id);
                $data->update(
                    [
                        'room' => $request->room,
                        'purpose' => $request->purpose,
                        'starttime' => $request->starttime,
                        'endtime' => $request->endtime,
                        'date' => $request->date,
                        'note' => $request->note,
                        'status' => $request->status
                    ]
                );
            }
        }

        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function destroy($id)
    {
        BookingRoom::find($id)->delete();
        return response()->json(['success' => 'Data telah berhasil dihapus']);
    }
}
