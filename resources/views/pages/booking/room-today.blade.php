@extends('adminlte::page')

@section('title', 'Booking System')

@section('content_header')
    <div class="container">
        <div class="row">
            <div class="col float-left">
                <h5><i class="fa fa-chalkboard"></i> <strong>Ruang Meeting</strong> | {{ $date }}</h5>
            </div>
        </div>
    </div>
@stop

@section('content')
    <table class="table table-striped data-table display nowrap" width="100%">
        <thead>
            <tr>
                {{-- <th width="50px">#</th> --}}
                <th>ID Booking</th>
                <th>Foto</th>
                <th>Ruangan</th>
                <th>Kegiatan</th>
                <th>Waktu</th>
                <th>PIC</th>
                <th>Jumlah</th>
                {{-- <th>Status</th> --}}
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
                ajax: '{!! route('bookingroom.datatoday') !!}',
                columnDefs: [{
                    searchable: false,
                    orderable: false,
                    targets: 0,
                }, ],
                order: [
                    [0, 'asc']
                ],
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex'
                    // },
                    {
                        data: 'booking_id',
                        name: 'booking_id'
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
                    //     data: 'status',
                    //     name: 'status'
                    // },

                ]
            });

            $('body').on('click', '.showQty', function() {
                var data_id = $(this).data("id");
                window.location.href = '{{ route('detailbookingroom.index') }}' + '/' + data_id + '/show';
            });

            function loadlink() {
                $('#reload');
                table.draw();
            }

            loadlink();
            setInterval(function() {
                loadlink()
            }, 15000);
        });
    </script>
@stop
