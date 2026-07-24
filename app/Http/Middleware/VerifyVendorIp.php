<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyVendorIp
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = $request->user();

        if (!$vendor || !$vendor->is_active) {
            return response()->json(['message' => 'Unauthorized or Inactive Vendor.'], 403);
        }

        // Jika fitur bypass diaktifkan, langsung loloskan
        if ($vendor->bypass_ip_whitelist) {
            return $next($request);
        }

        // Jika tidak di-bypass, periksa apakah array allowed_ips ada dan valid
        $allowedIps = $vendor->allowed_ips ?? [];

        if (!in_array($request->ip(), $allowedIps)) {
            return response()->json([
                'message' => 'IP Address Mismatch. Access Denied.',
                'your_ip' => $request->ip() // Opsional: bantu mereka tahu IP mereka sendiri saat error
            ], 403);
        }

        return $next($request);
    }
}
