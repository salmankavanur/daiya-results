<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$results = \App\Models\ExamResult::all();
foreach($results as $r) {
    if(is_array($r->marks_data)) {
        foreach($r->marks_data as $sub => $val) {
            \App\Models\BatchSubject::firstOrCreate(
                ['batch' => $r->batch, 'name' => $sub],
                ['max_te' => 100, 'max_ce' => 0, 'pass_mark' => 35]
            );
        }
    }
}
echo "Done!\n";
