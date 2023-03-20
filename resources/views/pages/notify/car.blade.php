@component('mail::message')
# Permohonan Booking Kendaraan

Ada permohonan booking kendaraan, Anda dapat melihatnya disini:

@component('mail::button', ['url' => $url])
    Lihat permohonan
@endcomponent

## Keterangan:

@component('mail::table')
    |               |                           |
    | ------------- | -------------------------:|
    | Pemohon       | {{ $data->user }}         |
    | Keperluan     | {{ $data->purpose }}      |
    | Tujuan        | {{ $data->destination }}  |
    | Tanggal       | {{ $data->datedepature }} |
    | Waktu         | {{ $data->timedepature }} |
@endcomponent

@endcomponent
