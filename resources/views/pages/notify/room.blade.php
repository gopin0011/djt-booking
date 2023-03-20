@component('mail::message')
# Permohonan Booking Ruangan

Ada permohonan booking ruangan, Anda dapat melihatnya disini:

@component('mail::button', ['url' => $url])
    Lihat permohonan
@endcomponent

## Keterangan:

@component('mail::table')
    |               |                           |
    | ------------- | -------------------------:|
    | Pemohon       | {{ $data->user }}         |
    | Keperluan     | {{ $data->purpose }}      |
    | Ruangan       | {{ $data->room }}         |
    | Tanggal       | {{ $data->date }}         |
    | Mulai         | {{ $data->starttime }}    |
    | Akhir         | {{ $data->endtime }}      |
@endcomponent

@endcomponent
