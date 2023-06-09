<?php

namespace App\Http\Controllers;

use App\Mail\RoomBooking;
use App\Models\BookingRoom;
use App\Models\DetailBookingRoom;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class BookingRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('status', '0')->get();
        $carousels = Room::where([['status', '0'], ['id', '<>', '1']])->get();
        return view('pages.booking.room', compact('rooms', 'carousels'));
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
                ->addColumn('image', function ($row) {
                    $room = Room::find($row->room);
                    if ($room->image) {
                        $data = $room->image;
                        $x = asset('storage/room/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img class="tongji" src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img class="tongji" src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
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
                    $data = DetailBookingRoom::where('booking_id', $row->id)->get();
                    if ($row->status == '0') {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Add" class="add btn btn-primary btn-sm addQty"><i class="fa fa-add"></i></a>';
                    } else {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Show" class="show btn btn-success btn-sm showQty"><i class="fa fa-magnifying-glass"></i></a>';
                    }
                    $qty = $btn . ' ' . count($data);
                    return $qty;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = "Belum Diproses";
                    } else if ($row->status == 1) {
                        $status = "Diterima";
                    } else if ($row->status == 2) {
                        $status = "Ditolak";
                    } else {
                        $status = "Selesai";
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    if ($row->status == 1) {
                        $btn = "Diterima";
                    } else if ($row->status == 2) {
                        $btn = "Ditolak";
                    } else {
                        if ($row->note == '') {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Review" class="review btn btn-warning btn-sm reviewData">Beri Ulasan</a>';
                        } else {
                            $btn = "Selesai";
                        }
                    }

                    if ($row->status == '0') {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editData"><i class="fa fa-edit"></i></a>';
                        $btn .= '&nbsp;&nbsp;';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image', 'time', 'pic', 'qty', 'status', 'action'])
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
                ->addColumn('image', function ($row) {
                    $room = Room::find($row->room);
                    if ($room->image) {
                        $data = $room->image;
                        $x = asset('storage/room/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img class="tongji" src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img class="tongji" src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
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
                    $data = DetailBookingRoom::where('booking_id', $row->id)->get();
                    $qty = count($data);
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Show" class="show btn btn-success btn-sm showQty"><i class="fa fa-magnifying-glass"></i></a>';
                    $qty = $btn . ' ' . count($data);
                    return $qty;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = "Belum Diproses";
                    } else if ($row->status == 1) {
                        $status = "Diterima";
                    } else if ($row->status == 2) {
                        $status = "Ditolak";
                    } else {
                        $status = "Selesai";
                    }
                    return $status;
                })
                ->rawColumns(['image', 'time', 'pic', 'qty', 'status'])
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
                    $data = DetailBookingRoom::where('booking_id', $row->id)->get();
                    $qty = count($data);
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Show" class="show btn btn-success btn-sm showQty"><i class="fa fa-magnifying-glass"></i></a>';
                    $qty = $btn . ' ' . count($data);
                    return $qty;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = "Belum Diproses";
                    } else if ($row->status == 1) {
                        $status = "Diterima";
                    } else if ($row->status == 2) {
                        $status = "Ditolak";
                    } else {
                        $status = "Selesai";
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
        $data = BookingRoom::where([['status', '<>', '2'], ['date', '>=', date(now()->format('Y-m-d'))], ['starttime', '>=', date(now()->format('H:i:s'))]])->get();
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
                    $data = DetailBookingRoom::where('booking_id', $row->id)->get();
                    $qty = count($data);
                    return $qty;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = "Belum Diproses";
                    } else if ($row->status == 1) {
                        $status = "Diterima";
                    } else if ($row->status == 2) {
                        $status = "Ditolak";
                    } else {
                        $status = "Selesai";
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
                $bookingId = 'BR-' . date(now()->format('YmdHis'));
                BookingRoom::updateOrCreate(
                    ['id' => $request->data_id],
                    [
                        'booking_id' => $bookingId,
                        'room' => $request->room,
                        'purpose' => $request->purpose,
                        'starttime' => $request->starttime,
                        'endtime' => $request->endtime,
                        'date' => $request->date,
                        'pic' => Auth::user()->id,
                        // 'qty' => $request->qty,
                        'note' => '',
                        'status' => '0'
                    ]
                );

                $targetEmail = User::where('role', '1')->orWhere('role', '2')->get('email');
                $data = DB::table('booking_rooms')
                    ->join('users', 'users.id', '=', 'booking_rooms.pic')
                    ->join('rooms', 'rooms.id', '=', 'booking_rooms.room')
                    ->where('booking_rooms.booking_id', $bookingId)
                    ->select('rooms.name as room', 'booking_rooms.purpose as purpose', 'booking_rooms.starttime as starttime', 'booking_rooms.endtime as endtime', 'booking_rooms.date as date', 'users.name as user',)
                    ->get()[0];
                $url = env('APP_URL') . '/booking-room/approve';

                Mail::to($targetEmail)->send(
                    new RoomBooking($data, $url)
                );
            }

            //BUAT NOTIFIKASI VIA EMAIL KE GA
        } else {
            $cek = BookingRoom::where([['room', $request->room], ['date', $request->date], ['starttime', '>=', $request->starttime], ['starttime', '<=', $request->endtime]])->orWhere([['room', $request->room], ['date', $request->date], ['endtime', '>=', $request->starttime], ['endtime', '<=', $request->endtime]])->orWhere([['room', $request->room], ['date', $request->date], ['starttime', '<=', $request->starttime], ['endtime', '>=', $request->endtime]])->get();

            if (count($cek) != 0) {
                $data = BookingRoom::find($request->data_id);
                $data->update(
                    [
                        'purpose' => $request->purpose,
                        // 'note' => '',
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
                        // 'note' => '',
                        'status' => $request->status
                    ]
                );
            }

            if ($request->status == '1') {
                //BUAT NOTIFIKASI UNDANGAN MEETING
            }
        }

        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function review(Request $request)
    {
        $data = BookingRoom::find($request->data_ids);
        $data->update(
            [
                'note' => $request->note
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
