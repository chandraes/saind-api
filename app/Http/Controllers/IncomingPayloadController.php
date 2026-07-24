<?php

namespace App\Http\Controllers;

use App\Models\PayloadInbox;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IncomingPayloadController extends Controller
{
    public function store(Request $request)
    {
     // 1. Validasi struktur dasar (hanya memastikan ada key 'data' berbentuk array)
        // Jangan validasi isi detail array-nya di sini agar API tidak lambat!
        $request->validate([
            'data' => 'required|array',
        ]);

        // 2. Buat ID unik (UUID) untuk menandai satu kesatuan pengiriman (batch)
        $batchId = Str::uuid()->toString();

        // 3. Simpan ke tabel staging (payload_inboxes)
        PayloadInbox::create([
            'vendor_id'   => $request->user()->id, // Diambil otomatis dari Bearer Token di Header
            'batch_id'    => $batchId,
            'source_ip'   => $request->ip(),
            'raw_payload' => $request->input('data'), // Disimpan utuh, Model akan otomatis menjadikannya JSON
            'status'      => 'pending'
        ]);

        // 4. TEMPAT JOB BACKGROUND (Akan kita isi di tahap selanjutnya)
        // ProcessPayloadJob::dispatch($batchId);

        // 5. Kembalikan respons sukses ke rekanan bahwa data masuk antrean
        return response()->json([
            'message'  => 'Payload received successfully.',
            'batch_id' => $batchId
        ], 202);
    }
}
