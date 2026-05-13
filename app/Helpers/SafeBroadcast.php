<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class SafeBroadcast
{
    /**
     * Safely broadcast an event. If Reverb is not running, log the error
     * instead of crashing the application.
     */
    public static function dispatch($event, bool $toOthers = false): void
    {
        try {
            if ($toOthers) {
                broadcast($event)->toOthers();
            } else {
                broadcast($event);
            }
        } catch (\Exception $e) {
            Log::warning('Broadcasting failed (Reverb may not be running): ' . $e->getMessage());
        }
    }
}
