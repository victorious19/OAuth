<?php

return [

    'google' => [
        'client_id' => env('GOOGLE_ID'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect' => 'http://127.0.0.1:8000/api/auth/google/callback',
    ],
];
