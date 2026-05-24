<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class GoogleSheetSyncSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'auto_sync_enabled' => 'boolean',
        'sync_interval_minutes' => 'integer',
        'last_synced_at' => 'datetime',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'google_sheet_url' => null,
                'auto_sync_enabled' => false,
                'sync_interval_minutes' => 30,
                'last_synced_at' => null,
            ]
        );
    }

    public function isDueForSync(?CarbonInterface $now = null): bool
    {
        if (! $this->auto_sync_enabled) {
            return false;
        }

        $url = trim((string) $this->google_sheet_url);
        if ($url === '') {
            return false;
        }

        if ($this->last_synced_at === null) {
            return true;
        }

        $now = $now ?? now();
        $minutes = max(1, (int) $this->sync_interval_minutes);

        return $this->last_synced_at->copy()->addMinutes($minutes)->lte($now);
    }
}

