<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IncomingPayloadController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi struktur dasar (hanya memastikan formatnya array)
        // Kita TIDAK memvalidasi isi array di sini.
        $request->validate([
            'data' => 'required|array',
            // Tambahkan rule lain jika mereka mengirim metadata (misal: total_rows)
        ]);

        // 2. Buat ID unik untuk request ini
        $batchId = Str::uuid()->toString();

        // 3. Simpan ke staging table dengan kecepatan maksimal
        // Menggunakan json_encode karena request->input('data') sudah berupa array di PHP
        \App\Models\PayloadInbox::create([
            'batch_id'    => $batchId,
            'source_ip'   => $request->ip(),
            'raw_payload' => $request->input('data'),
            'status'      => 'pending'
        ]);

        // 4. (OPSIONAL TAPI SANGAT DISARANKAN)
        // Jika Anda sudah menyalakan Laravel Queue, Anda bisa men-trigger Job di sini.
        // ProcessPayloadJob::dispatch($batchId);

        // 5. Kembalikan 202 Accepted.
        // 202 berarti "Kami terima, tapi belum selesai kami proses."
        // Ini adalah HTTP status code yang paling tepat untuk kasus ini, bukan 200 atau 201.
        return response()->json([
            'message'  => 'Payload received successfully.',
            'batch_id' => $batchId
        ], 202);
    }
}
