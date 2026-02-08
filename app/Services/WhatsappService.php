<?php

namespace App\Services;

use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Http;

class WhatsappService
{
    /**
     * Send a WhatsApp message.
     * Currently mocked for development but logs to database.
     */
    public static function send($phoneNumber, $message)
    {
        $tenantId = auth()->user()->tenant_id ?? null;

        // Mocking API call to WhatsApp Gateway (e.g., Fonnte)
        // In production:
        // $response = Http::withHeaders(['Authorization' => 'TOKEN'])->post('api', [...]);

        $log = WhatsappLog::create([
            'tenant_id' => $tenantId,
            'phone_number' => $phoneNumber,
            'message' => $message,
            'status' => 'sent', // Mocked as sent
            'sent_at' => now(),
        ]);

        return $log;
    }

    /**
     * Pre-defined templates for notifications.
     */
    public static function notifyTahfidz($studentName, $surah, $status)
    {
        $msg = "Assalamualaikum Bapak/Ibu, ananda *{$studentName}* baru saja menyelesaikan setoran Tahfidz: *{$surah}* dengan status *{$status}*. Mohon doanya agar ananda istiqomah. - SIM-PP";
        // return self::send($phone, $msg);
        return $msg;
    }
}
