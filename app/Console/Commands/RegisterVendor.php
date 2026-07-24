<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use App\Models\Vendor;
use Illuminate\Console\Command;

#[Signature('vendor:register {name} {saind_id} {ips?}')]
#[Description('Mendaftarkan vendor baru dan men-generate API Token Sanctum')]
class RegisterVendor extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $ipsInput = $this->argument('ips');
        $saindId = $this->argument('saind_id');

        if (Vendor::where('name', $name)->exists()) {
            $this->error("Vendor '{$name}' sudah ada!");
            return self::FAILURE;
        }

        if ($saindId && Vendor::where('saind_id', $saindId)->exists()) {
            $this->error("Vendor dengan saind_id '{$saindId}' sudah ada!");
            return self::FAILURE;
        }

        $bypass = false;
        $allowedIps = null;

        if (strtoupper($ipsInput) === 'BYPASS' || empty($ipsInput)) {
            $bypass = true;
            $this->warn("PERINGATAN: Whitelist IP dimatikan untuk vendor ini.");
        } else {
            // Pecah string berdasar koma, hilangkan spasi
            $allowedIps = array_map('trim', explode(',', $ipsInput));
        }

        $vendor = Vendor::create([
            'name'                => $name,
            'saind_id'            => $saindId,
            'allowed_ips'         => $allowedIps,
            'bypass_ip_whitelist' => $bypass,
            'is_active'           => true,
        ]);

        $token = $vendor->createToken('integration-token');

        $this->info("Vendor '{$name}' terdaftar.");
        $this->info("Token: " . $token->plainTextToken);

        return self::SUCCESS;
    }
}
