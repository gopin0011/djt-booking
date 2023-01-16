<?php

namespace App\Http\Controllers;

use App\Models\BookingCar;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BookingCarController extends Controller
{
    public function index()
    {
        $cars = Car::where('status', '0')->get();
        return view('pages.booking.car', compact('cars'));
    }

    public function showData(Request $request)
    {
        $data = BookingCar::where('user', Auth::user()->id)->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('car', function ($row) {
                    $data = Car::find($row->car);
                    return $data->number;
                })
                ->addColumn('driver', function ($row) {
                    $data = User::find($row->driver);
                    if ($row->driver == '') {
                        $driver = 'Belum ada driver';
                    } else {
                        $driver = $data->name;
                    }
                    return $driver;
                })
                ->addColumn('user', function ($row) {
                    $data = User::find($row->user);
                    return $data->name;
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
                        if ($row->rating == '') {
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
                ->addColumn('rating', function ($row) {
                    if ($row->rating == '') {
                        $rating = "Belum ada ulasan";
                    } else {
                        $rating = '( ⭐ ' . $row->rating . ' ) ' . $row->note;
                    }
                    return $rating;
                })
                ->rawColumns(['car', 'driver', 'user', 'status', 'action', 'rating'])
                ->make(true);
            return $allData;
        }
    }

    public function today()
    {
        $date = Carbon::now()->format('l, j F Y');
        return view('pages.booking.car-today', compact('date'));
    }

    public function approve()
    {
        $cars = Car::where('status', '0')->get();
        $drivers = User::where([['status', '0'], ['role', '5']])->get();
        return view('pages.booking.car-approve', compact('cars', 'drivers'));
    }

    public function approveData(Request $request)
    {
        $data = BookingCar::query();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('time', function ($row) {
                    $time = $row->timedepature;
                    return $time;
                })
                ->addColumn('driver', function ($row) {
                    $data = User::find($row->driver);
                    if ($row->driver == '') {
                        $driver = 'Belum ada driver';
                    } else {
                        $driver = $data->name;
                    }
                    return $driver;
                })
                ->addColumn('car', function ($row) {
                    $car = Car::find($row->car);
                    return $car->number;
                })
                ->addColumn('user', function ($row) {
                    $user = User::find($row->user);
                    return $user->name;
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
                ->rawColumns(['time', 'driver', 'car', 'user', 'status', 'action'])
                ->make(true);
            return $allData;
        }
    }

    public function showDataToday(Request $request)
    {
        $data = BookingCar::where('datedepature', date(now()->format('Y-m-d')))->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('car', function ($row) {
                    $data = Car::find($row->car);
                    return $data->number;
                })
                ->addColumn('driver', function ($row) {
                    $data = User::find($row->driver);
                    if ($row->driver == '') {
                        $driver = 'Belum ada driver';
                    } else {
                        $driver = $data->name;
                    }
                    return $driver;
                })
                ->addColumn('user', function ($row) {
                    $data = User::find($row->user);
                    return $data->name;
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
                ->rawColumns(['car', 'driver', 'user', 'status'])
                ->make(true);
            return $allData;
        }
    }

    public function edit($id)
    {
        $data = BookingCar::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        if ($request->data_id == '') {
            BookingCar::updateOrCreate(
                ['id' => $request->data_id],
                [
                    'booking_id' => 'BC-' . date(now()->format('YmdHis')),
                    'car' => '1',
                    'driver' => '',
                    'destination' => $request->destination,
                    'purpose' => $request->purpose,
                    'timedepature' => $request->timedepature,
                    'datedepature' => $request->datedepature,
                    'user' => Auth::user()->id,
                    'qty' => $request->qty,
                    'status' => '0',
                    'rating' => '',
                    'note' => ''
                ]
            );
        } else {
            $data = BookingCar::find($request->data_id);
            $data->update(
                [
                    'car' => $request->car,
                    'driver' => $request->driver,
                    'destination' => $request->destination,
                    'purpose' => $request->purpose,
                    'timedepature' => $request->timedepature,
                    'datedepature' => $request->datedepature,
                    'qty' => $request->qty,
                    'status' => $request->status
                ]
            );
        }

        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function review(Request $request)
    {
        $data = BookingCar::find($request->data_ids);

        if($request->rating <= 1)
        {
            $rating = 1;
        }else if ($request->rating >= 5)
        {
            $rating = 5;
        }else{
            $rating = $request->rating;
        }

        $data->update(
            [
                'rating' => $rating,
                'note' => $request->note
            ]
        );

        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function destroy($id)
    {
        BookingCar::find($id)->delete();
        return response()->json(['success' => 'Data telah berhasil dihapus']);
    }
}
