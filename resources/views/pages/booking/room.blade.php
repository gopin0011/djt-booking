@extends('adminlte::page')

@section('title', 'Booking System')

@section('content_header')
    <div class="container">
        <div class="row">
            <div class="col float-left">
                <h5><i class="fa fa-bookmark"></i> <strong>Booking Ruang Meeting</strong></h5>
            </div>
            <div class="col">
                <a type="button" href="javascript:void(0)" id="createNewData" class="btn btn-primary float-right">+ Tambah</a>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModal" arial-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="dataForm" name="dataForm" class="form-horizontal">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    <input type="hidden" name="data_id" id="data_id">
                                    <input type="hidden" name="status" id="status">
                                    <div class="input-group mb-3">
                                        <div class="col-sm-12">
                                            <label for="name">Pilih Ruangan</label><br>
                                            <select type="text" class="form-control" id="room" name="room"
                                                value="">
                                                @foreach ($rooms as $data)
                                                    <option value="{{ $data->id }}">
                                                        {{ $data->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-sm-4">
                                            <label for="type">Jumlah Peserta</label><br>
                                            <input type="number" class="form-control" value="1" name="qty"
                                                id="qty">
                                        </div> --}}
                                    </div>

                                    <div class="input-group mb-3">

                                        <div class="col-sm-4">
                                            <label for="type">Tanggal Meeting</label><br>
                                            <input type="date" class="form-control" name="date" id="date">
                                        </div>

                                        <div class="col-sm-4">
                                            <label for="type">Waktu Mulai</label><br>
                                            <input type="time" class="form-control" name="starttime" id="starttime">
                                        </div>

                                        <div class="col-sm-4">
                                            <label for="type">Waktu Selesai</label><br>
                                            <input type="time" class="form-control" name="endtime" id="endtime">
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="col-sm-12">
                                            <label for="number">Kegiatan Meeting</label><br>
                                            <textarea rows="2" class="form-control" name="purpose" id="purpose"></textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="input-group mb-3">
                                        <div class="col-sm-12">
                                            <label for="note">Catatan</label><br>
                                            <textarea rows="3" class="form-control" name="note" id="note"></textarea>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <button type="submit" class="btn btn-primary btn-block" id="btnSave"
                                                value="create">Booking Ruangan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajaxModalReview" arial-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeadingReview"></h4>
                </div>
                <div class="modal-body">
                    <form id="dataFormReview" name="dataFormReview" class="form-horizontal">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    <input type="hidden" name="data_ids" id="data_ids">
                                    <div class="input-group mb-3">
                                        <div class="col-sm-12">
                                            <label for="note">Ulasan</label><br>
                                            <textarea rows="3" class="form-control" name="note" id="note" placeholder="Ulasan driver"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-primary btn-block" id="btnSaveReview"
                                            value="create">Beri Ulasan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div id="carouselExampleCaptions" class="carousel slide mb-3" data-ride="carousel">

        <ol class="carousel-indicators">
            @foreach ($data as $value)
                <li data-target=".carouselExampleCaptions" data-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach ($carousels as $value)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ is_null($value->image) ? asset('default.png') : asset('storage/room/' . $value->image) }}"
                        class="d-block w-100" alt="..." height="450">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $value->name }}</h5>
                        <p>{{ $value->note }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <table class="table table-striped data-table display nowrap" width="100%">
        <thead>
            <tr>
                {{-- <th width="50px">#</th> --}}
                <th width="60px">Status</th>
                <th>Foto</th>
                <th>Ruangan</th>
                <th>Kegiatan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>PIC</th>
                <th>Jumlah</th>
                {{-- <th>Catatan</th> --}}
                <th>ID Booking</th>
                <th>Ulasan</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

@section('css')
    <style>
        .tongji {
            width: 100px;
            height: 70px;
            object-fit: cover;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" />
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $(".data-table").DataTable({
                // lengthMenu:[[10,25,50,-1],['10', '25', '50', 'Show All']],
                // dom: 'Blfrtip',
                // buttons: [
                //     'excel'
                // ],
                // rowReorder: {
                //     selector: 'td:nth-child(2)'
                // },
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: '{!! route('bookingroom.data') !!}',
                columnDefs: [{
                    searchable: false,
                    orderable: false,
                    targets: 0,
                }, ],
                order: [
                    [8, 'asc']
                ],
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex'
                    // },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'room',
                        name: 'room'
                    },
                    {
                        data: 'purpose',
                        name: 'purpose'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                    {
                        data: 'pic',
                        name: 'pic'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    // {
                    //     data: 'note',
                    //     name: 'note'
                    // },
                    // {
                    //     data: 'status',
                    //     name: 'status'
                    // },
                    {
                        data: 'booking_id',
                        name: 'booking_id'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },

                ]
            });

            $("#createNewData").click(function() {
                $("#data_id").val('');
                $("#dataForm").trigger("reset");
                $("#modalHeading").html("Form Booking");
                $("#ajaxModal").modal('show');
            });

            $("#btnSave").click(function(e) {
                e.preventDefault();
                $(this).html('Booking Ruangan');

                $.ajax({
                    type: "POST",
                    url: "{{ route('bookingroom.store') }}",
                    data: $("#dataForm").serialize(),
                    dataType: 'json',
                    success: function(data) {
                        $("#dataForm").trigger("reset");
                        $("#ajaxModal").modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error', data);
                        $("#btnSave").html('Booking Ruangan');
                    }
                });
            });

            $('body').on('click', '.deleteData', function() {
                var data_id = $(this).data("id");
                if (confirm("Apakah Anda yakin?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('bookingroom.store') }}" + "/" + data_id,
                        success: function(data) {
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error', data);
                        }
                    });
                } else {
                    return false;
                }
            });

            $('body').on('click', '.editData', function() {
                var data_id = $(this).data("id");
                $.get("{{ route('bookingroom.index') }}" + "/" + data_id + "/edit", function(data) {
                    $("#modalHeading").html("Form Booking");
                    $("#ajaxModal").modal('show');
                    $("#data_id").val(data.id);
                    $("#room").val(data.room);
                    $("#purpose").val(data.purpose);
                    $("#starttime").val(data.starttime);
                    $("#endtime").val(data.endtime);
                    $("#date").val(data.date);
                    $("#pic").val(data.pic);
                    $("#qty").val(data.qty);
                    $("#note").val(data.note);
                    $("#status").val(data.status);
                });
            });

            $('body').on('click', '.reviewData', function() {
                var data_id = $(this).data("id");
                $.get("{{ route('bookingroom.index') }}" + "/" + data_id + "/edit", function(data) {
                    $("#modalHeadingReview").html("Beri Ulasan");
                    $("#ajaxModalReview").modal('show');
                    $("#data_ids").val(data.id);
                    $("#note").val(data.note);
                });
            });

            $("#btnSaveReview").click(function(e) {
                e.preventDefault();
                $(this).html('Beri Ulasan');

                $.ajax({
                    type: "POST",
                    url: "{{ route('bookingroom.review') }}",
                    data: $("#dataFormReview").serialize(),
                    dataType: 'json',
                    success: function(data) {
                        $("#dataFormReview").trigger("reset");
                        $("#ajaxModalReview").modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error', data);
                        $("#btnSaveReview").html('Beri Ulasan');
                    }
                });
            });

            $('body').on('click', '.addQty', function() {
                var data_id = $(this).data("id");
                window.location.href = '{{ route('detailbookingroom.index') }}' + '/' + data_id +
                    '/detail';
            });

            $('body').on('click', '.showQty', function() {
                var data_id = $(this).data("id");
                window.location.href = '{{ route('detailbookingroom.index') }}' + '/' + data_id + '/show';
            });
        });
    </script>
@stop
