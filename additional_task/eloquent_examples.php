<?php

use App\Models\Submission;
use Illuminate\Support\Facades\DB;

:
$subs = Submission::query()
    ->whereRaw("JSON_TYPE(JSON_EXTRACT(answers, '$.will.trustees')) = 'ARRAY'")
    ->whereRaw("(
        SELECT COUNT(*)
        FROM JSON_TABLE(answers, '$.will.trustees[*]' COLUMNS(
            gender VARCHAR(10) PATH '$.gender'
        )) AS t
        WHERE LOWER(t.gender) = 'female'
    ) >= 2")
    ->get();


$subsPhp = Submission::all()->filter(function ($s) {
    $answers = $s->answers; 
    $trustees = data_get($answers, 'will.trustees', []);
    $femaleCount = collect($trustees)->filter(fn($t) => strtolower($t['gender'] ?? '') === 'female')->count();
    return $femaleCount >= 2;
});


