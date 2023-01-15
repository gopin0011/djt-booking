<?php

namespace App\Http\Controllers;

use App\Models\BookingCar;
use App\Models\BookingRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //CRUD
    public function index()
    {
        return view('pages.user.index');
    }

    public function driver()
    {
        return view('pages.user.driver');
    }

    public function showData(Request $request)
    {
        $data = User::where([['id', '<>', 1],['role','<>','5']])->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    if ($row->role == 0) {
                        $role = "Superadmin";
                    } else if ($row->role == 1) {
                        $role = "GA Manager";
                    } else if ($row->role == 2) {
                        $role = "GA Staff";
                    } else if ($row->role == 3) {
                        $role = "Front Office";
                    } else if ($row->role == 4) {
                        $role = "Department";
                    } else if ($row->role == 5) {
                        $role = "Driver";
                    } else {
                        $role = "Guest";
                    }
                    return $role;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0)
                    {
                        $status = "Aktif";
                    }else{
                        $status = "Nonaktif";
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $trans = BookingCar::where('driver', $row->id)->orWhere('user', $row->id)->get();
                    $count = count($trans);
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editData"><i class="fa fa-edit"></i></a>';
                    $btn .= '&nbsp;&nbsp;';
                    if ($count == 0) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['role', 'status', 'action'])
                ->make(true);
            return $allData;
        }
    }

    public function showDataDriver(Request $request)
    {
        $data = User::where('role', '5')->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status == 0)
                    {
                        $status = "Aktif";
                    }else{
                        $status = "Nonaktif";
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $trans = BookingCar::where('driver', $row->id)->orWhere('user', $row->id)->get();
                    $count = count($trans);
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editData"><i class="fa fa-edit"></i></a>';
                    $btn .= '&nbsp;&nbsp;';
                    if ($count == 0) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
            return $allData;
        }
    }

    public function edit($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        if ($request->data_id == "") {
            User::updateOrCreate(
                ['id' => $request->data_id],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'nik' => $request->nik,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'role' => $request->role,
                    'password' => bcrypt("12345678"),
                    'status' => $request->status,
                ]
            );
        } else {
            User::updateOrCreate(
                ['id' => $request->data_id],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'nik' => $request->nik,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'role' => $request->role,
                    'status' => $request->status,
                ]
            );
        }
        return response()->json(['success' => 'Data telah berhasil disimpan']);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success' => 'Data telah berhasil dihapus']);
    }

    //DATA
    public function changedata(){
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('pages.user.changedata', compact('data'));
    }

    public function storedata(Request $request){

            User::find(auth()->user()->id)->update([
                'name'=> $request->name,
                'nik'=> $request->nik,
                'phone'=> $request->phone,
                'address'=> $request->address
            ]);
            return redirect(route('home'));
    }

    //PASSWORD
    public function changepassword(){
        return view('pages.user.changepassword');
    }

    public function storepassword(Request $request){
        $validate = $request->validate([
            'old' => ['required', new MatchOldPassword],
            'new' => ['required', 'string', 'min:8'],
            'conf' => ['same:new'],
        ]);

        if($validate)
        {
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new)]);
            // toast('Password telah berhasil diubah','success');
            Auth::logout();
            return redirect(route('login'));
        }
        else{
            // toast('Gagal ubah password','error');
            return redirect(route('changepassword'));
        }
    }
}
