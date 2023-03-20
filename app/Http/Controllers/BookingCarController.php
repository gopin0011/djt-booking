<?php

namespace App\Http\Controllers;

use App\Mail\CarBooking;
use App\Models\BookingCar;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

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
                ->addColumn('image', function ($row) {
                    $car = Car::find($row->car);
                    if ($car->image) {
                        $data = $car->image;
                        $x = asset('storage/car/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img class="tongji" src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img class="tongji" src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
                })
                ->addColumn('photo', function ($row) {
                    $driver = User::find($row->driver);
                    if ($driver->image) {
                        $data = $driver->image;
                        $x = asset('storage/driver/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img class="cang" src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img class="cang" src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
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
                ->rawColumns(['photo', 'car', 'image', 'driver', 'user', 'status', 'action', 'rating'])
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
                ->addColumn('image', function ($row) {
                    $car = Car::find($row->car);
                    if ($car->image) {
                        $data = $car->image;
                        $x = asset('storage/car/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img class="tongji" src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img class="tongji" src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
                })
                ->addColumn('photo', function ($row) {
                    $driver = User::find($row->driver);
                    if ($driver->image) {
                        $data = $driver->image;
                        $x = asset('storage/driver/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img class="cang" src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img class="cang" src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
                })
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
                ->rawColumns(['image', 'photo', 'car', 'driver', 'user', 'status'])
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
        // error_log($request->data_id);
        if ($request->data_id == '') {
            $bookingId = 'BC-' . date(now()->format('YmdHis'));
            BookingCar::updateOrCreate(
                ['id' => $request->data_id],
                [
                    'booking_id' => $bookingId,
                    'car' => '1',
                    'driver' => '1',
                    'destination' => $request->destination,
                    'purpose' => $request->purpose,
                    'timedepature' => $request->timedepature,
                    'timearrive' => '-',
                    'datedepature' => $request->datedepature,
                    'user' => Auth::user()->id,
                    'qty' => $request->qty,
                    'status' => '0',
                    'rating' => '',
                    'note' => ''
                ]
            );

            $targetEmail = User::where('role', '1')->orWhere('role', '2')->get('email');
            $data = DB::table('booking_cars')
                ->join('users', 'users.id', '=', 'booking_cars.user')
                ->where('booking_cars.booking_id', $bookingId)
                ->select('booking_cars.id as id', 'booking_cars.status as status', 'booking_cars.destination as destination', 'booking_cars.purpose as purpose', 'booking_cars.timedepature as timedepature', 'booking_cars.timearrive as timearrive', 'booking_cars.datedepature as datedepature', 'users.name as user',)
                ->get()[0];
            $url = env('APP_URL') . '/booking-vehicle/approve';


            Mail::to($targetEmail)->send(
                new CarBooking($data, $url)
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
                    'timearrive' => '-',
                    'datedepature' => $request->datedepature,
                    'qty' => $request->qty,
                    'status' => $request->status
                ]
            );

            if ($request->status == '1') {
                $url = 'https://fcm.googleapis.com/fcm/send';
                $FcmToken = User::select('device_token')->where('id', $request->driver)->whereNotNull('device_token')->pluck('device_token')->all();
                $serverKey = 'AAAAtqnqJAg:APA91bHRqs3sHpLmya-pqxpLP53MxrCACfIPrl_eyuSRPyBh8LsSw1ubwEvaRvCqZMTjpP-Quy60mU7N3yCRm2f4p4Vc7y8cr5RZwu8WJKW82U2d5jWEZKJaNpj4QjIBjcEWHnv2SaW0';

                $data = [
                    "registration_ids" => $FcmToken,
                    "notification" => [
                        "title" => 'BOOKING SYSTEM - Drive Me',
                        "body" => 'Ada tugas baru untuk Anda, segera cek aplikasi!',
                        "sound" => true
                    ]
                ];

                $encodedData = json_encode($data);

                $headers = [
                    'Authorization:key=' . $serverKey,
                    'Content-Type: application/json',
                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                // Disabling SSL Certificate support temporarly
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
                // Execute post
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }
                // Close connection
                curl_close($ch);
                // return response()->json(['success'=>'Data telah berhasil ditambah']);
            }
        }

        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function review(Request $request)
    {
        $data = BookingCar::find($request->data_ids);

        if ($request->rating <= 1) {
            $rating = 1;
        } else if ($request->rating >= 5) {
            $rating = 5;
        } else {
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
