<?php

namespace App\Helpers;

use App\Models\Bitacora;
use App\Models\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class LoggerHelper
{
    /**
     * Log an action in the database audit tables.
     *
     * @param string $tipo E.g., 'AUTH', 'CREATE', 'UPDATE', 'DELETE', 'PROCESS'
     * @param string $descripcion Summary of what happened
     * @param string|null $accion Detailed action / data changed
     * @param int|null $userId Optional user ID override (for login actions)
     * @return void
     */
    public static function log(string $tipo, string $descripcion, ?string $accion = null, ?int $userId = null)
    {
        $uid = $userId ?? Auth::id();

        // If no user is logged in and no override is provided, we can't log to a specific user.
        // We skip or log to a default system user (e.g. 1) if possible, but standard is to require a user.
        if (!$uid) {
            return;
        }

        try {
            // 1. Create Bitacora entry
            $bitacora = Bitacora::create([
                'tipo' => $tipo,
                'descripcion' => $descripcion,
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString(),
                'estado' => 'Activo',
                'id_usuario' => $uid,
            ]);

            // 2. Create DetalleBitacora entry
            DetalleBitacora::create([
                'id_bitacora' => $bitacora->id_bitacora,
                'direccion_ip' => request()->ip() ?? '127.0.0.1',
                'hora_inicio' => now()->toTimeString(),
                'hora_fin' => now()->toTimeString(),
                'accion' => $accion ?? $descripcion,
            ]);
        } catch (\Exception $e) {
            \Log::error("Audit log failed: " . $e->getMessage());
        }
    }
}
