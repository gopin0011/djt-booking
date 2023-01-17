@extends('adminlte::page')

@section('title', 'Booking System')

@section('content_header')
    <div class="container">
        <div class="row">
            <div class="col float-left">
                <h5><i class="fa fa-car"></i> <strong>Kendaraan</strong></h5>
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
                    <form id="dataForm" name="dataForm" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="data_id" id="data_id">
                        <div class="form-group">
                            <div class="d-flex justify-content-center">
                                <img id="modal-preview" src="default.png" alt="Preview" class="form-group hidden"
                                    width="150" height="150">
                            </div>
                            <div class="custom-file">
                                <input id="image" class="custom-file-input" type="file" name="image"
                                    accept="image/*" onchange="readURL(this);">
                                <label class="custom-file-label" for="exampleInputFile">Pilih foto</label>
                            </div>
                            <input type="hidden" name="hidden_image" id="hidden_image">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="number" name="number"
                                placeholder="Nomor polisi kendaraan" value="" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="note" name="note"
                                placeholder="Spesifikasi kendaraan" value="" required>
                        </div>
                        <div class="form-group">
                            Tipe: <br>
                            <select type="text" class="form-control" id="type" name="type" placeholder=""
                                value="">
                                <option value=Motor>Motor</option>
                                <option value=Mobil>Mobil</option>
                            </select>
                        </div>
                        <div class="form-group">
                            Status: <br>
                            <select type="text" class="form-control" id="status" name="status" placeholder=""
                                value="">
                                <option value=0>Aktif</option>
                                <option value=1>Nonaktif</option>
                            </select>
                            <br>
                        </div>
                        <button type="submit" class="btn btn-primary" id="btnSave" value="create">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <table class="table table-striped data-table display nowrap" width="100%">
        <thead>
            <tr>
                <th width="50px">#</th>
                <th>Foro Kendaraan</th>
                <th>Nomor Polisi</th>
                <th>Tipe</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th width="60px"></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

@section('css')
    <style>
        img {
            width: 200px;
            /* You can set the dimensions to whatever you want */
            height: 150px;
            object-fit: cover;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" />
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
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

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
                ajax: '{!! route('vehicles.data') !!}',
                columnDefs: [{
                    searchable: false,
                    orderable: false,
                    targets: 0,
                }, ],
                order: [
                    [2, 'asc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'note',
                        name: 'note',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ]
            });

            $("#createNewData").click(function() {
                $("#data_id").val('');
                $("#dataForm").trigger("reset");
                $("#modalHeading").html("Tambah Data");
                $("#ajaxModal").modal('show');
                $('#modal-preview').attr('src', 'default.png');
            });

            $('body').on('submit', '#dataForm', function(e) {
                e.preventDefault();
                var actionType = $('#btnSave').val();
                $('#btnSave').html('Simpan');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('vehicles.store') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $('#dataForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        $('#btnSave').html('Simpan');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#btnSave').html('Simpan');
                    }
                });
            });

            $('body').on('click', '.deleteData', function() {
                var data_id = $(this).data("id");
                if (confirm("Apakah Anda yakin?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('vehicles.store') }}" + "/" + data_id,
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
                $.get("{{ route('vehicles.index') }}" + "/" + data_id + "/edit", function(data) {
                    $("#modalHeading").html("Ubah Data");
                    $("#ajaxModal").modal('show');
                    $("#data_id").val(data.id);
                    $("#number").val(data.number);
                    $("#note").val(data.note);
                    $("#type").val(data.type);
                    $("#status").val(data.status);
                    $('#modal-preview').attr('src', 'default.png');
                    $('#modal-preview').attr('alt', 'No image available');
                    if (data.image) {
                        $('#modal-preview').attr('src', 'storage/room/' + data.image);
                        $('#hidden_image').val(data.image);
                    }
                });
            });
        });

        function readURL(input, id) {
            id = id || '#modal-preview';
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(id).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
                $('#modal-preview').removeClass('hidden');
                $('#start').hide();
            }
        }
    </script>

@stop
