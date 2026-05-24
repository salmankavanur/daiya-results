<?php

return [
    'google_sheet_url' => env('GOOGLE_SHEET_URL', ''),

    'google_sheet_auto_sync_enabled' => filter_var(
        env('GOOGLE_SHEET_AUTO_SYNC_ENABLED', false),
        FILTER_VALIDATE_BOOLEAN
    ),
];

