<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayloadInbox extends Model
{
    // Buka gembok keamanan untuk kolom-kolom ini agar bisa diisi via create()
    protected $fillable = [
        'vendor_id',
        'batch_id',
        'source_ip',
        'raw_payload',
        'status',
    ];

    // Beritahu Eloquent bahwa raw_payload adalah JSON/Array.
    // Saat nanti kita panggil di Job, Eloquent akan otomatis mengubahnya dari string JSON MariaDB menjadi Array PHP.
    protected $casts = [
        'raw_payload' => 'array',
    ];
}
