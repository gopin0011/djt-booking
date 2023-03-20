<?php

namespace App\Http\Controllers;

use App\Models\BookingCar;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordReset;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    // AAAAtqnqJAg:APA91bHRqs3sHpLmya-pqxpLP53MxrCACfIPrl_eyuSRPyBh8LsSw1ubwEvaRvCqZMTjpP-Quy60mU7N3yCRm2f4p4Vc7y8cr5RZwu8WJKW82U2d5jWEZKJaNpj4QjIBjcEWHnv2SaW0

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            // $success['token'] = $user->createToken('appToken')->accessToken;
            $success = $user->createToken('LoginToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'token' => $success,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }

    public function updatetoken(Request $request, $id)
    {
        $user = User::find($id);
        $user->update(['device_token' => $request->device_token]);
        return response()->json(['message' => 'Success', 'data' => $user]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['status ' => 'success', 'message' => "Logout successfully!", 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status ' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {
                $token = Str::random(64);
                $domain = URL::to('/');
                $url = $domain . '/password/reset/' . $token . '?email=' . $request->email;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = "Lupa Password";
                $data['body'] = "Klik tautan di bawah, untuk atur ulang password.";

                Mail::send('notify.forgot_password', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => bcrypt($token),
                        'created_at' => $datetime
                    ]
                );
                return response()->json(['success' => true, 'msg' => 'Cek email, untuk tautan pengaturan ulang password.']);
            } else {
                return response()->json(['success' => false, 'msg' => 'User tidak ditemukan!']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function passwordupdate(Request $request, $id)
    {
        // dd($request);
        $user = User::find($id);
        $validate = $request->validate([
            'old' => ['required', new MatchOldPassword],
            'new' => ['required', 'string', 'min:8'],
            'conf' => ['same:new'],
        ]);

        if ($validate) {
            $user->update(['password' => Hash::make($request->new)]);
            return response()->json(['message' => 'Success', 'data' => $user]);
        } else {
            // return response()->json(['message'=>'Failed','data'=>[]]);
        }
    }

    public function listBooking($id)
    {
        $data = DB::table('booking_cars')
            ->join('cars', 'cars.id', '=', 'booking_cars.car')
            ->join('users', 'users.id', '=', 'booking_cars.user')
            ->where('booking_cars.driver', $id)
            ->select('booking_cars.id as id', 'booking_cars.status as status', 'booking_cars.destination as destination', 'booking_cars.purpose as purpose', 'booking_cars.timedepature as timedepature', 'booking_cars.timearrive as timearrive', 'booking_cars.datedepature as datedepature', 'users.name as user', 'cars.number as car')
            ->get();
        return response()->json($data);
    }

    public function detailBooking($id)
    {
        $data = DB::table('booking_cars')
            ->join('cars', 'cars.id', '=', 'booking_cars.car')
            ->join('users', 'users.id', '=', 'booking_cars.user')
            ->where('booking_cars.id', $id)
            ->select('booking_cars.id as id', 'booking_cars.status as status', 'booking_cars.destination as destination', 'booking_cars.purpose as purpose', 'booking_cars.timedepature as timedepature', 'booking_cars.timearrive as timearrive', 'booking_cars.datedepature as datedepature', 'users.name as user', 'cars.number as car', 'cars.image as image')
            ->get();
        return response()->json($data);
    }

    public function selesai(Request $request, $id)
    {
        $data = BookingCar::find($id);
        $data->update($request->all());
        return response()->json(['success' => 'Data telah berhasil disimpan' . $data]);
    }
}
