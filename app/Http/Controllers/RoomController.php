<?php

namespace App\Http\Controllers;

use App\Models\BookingRoom;
use App\Models\Room;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class RoomController extends Controller
{
    public function index()
    {
        return view('pages.room.index');
    }

    public function showData(Request $request)
    {
        $data = Room::where('id', '<>', 1)->get();
        if ($request->ajax()) {
            $allData = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        $data = $row->image;
                        $x = asset('storage/room/' . $data);
                        $show = '<a href="' . $x . '" target="_blank"><div><img src="' . $x . '" alt=""></div></a>';
                    } else {
                        $show = '<div><img src="' . 'default.png' . '" alt=""></div>';
                    }
                    return $show;
                })
                ->addColumn('action', function ($row) {
                    $trans = BookingRoom::where('room', $row->id)->get();
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
        $data = Room::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'image' => 'image|file|max:2048',
            'name'  => 'required',
        ]);

        $dataId = $request->data_id;

        $details = [
            'name' => $request->name,
            'note' => $request->note,
            'status' => $request->status
        ];

        if ($files = $request->file('image')) {
            $file = 'storage/room/' . $request->hidden_image;
            File::delete(($file));

            $destinationPath = 'storage/room/';
            $profileImage = now()->format('ymdhis') . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $details['image'] = "$profileImage";
        }

        $dataUser   =   Room::updateOrCreate(['id' => $dataId], $details);
        return response()->json($dataUser);
    }

    public function destroy($id)
    {
        $data = Room::find($id);
        $file = 'storage/room/' . $data->image;
        File::delete(($file));
        $data->delete();
        return response()->json(['success' => 'Data telah berhasil dihapus']);
    }
}
