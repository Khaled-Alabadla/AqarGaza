<?php
return [
    'paths' => ['*'], // Cover your DELETE route
    'allowed_methods' => ['*'], // Allows GET, POST, DELETE, OPTIONS, etc.
    'allowed_origins' => ['*'], // Replace with frontend URL in production
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allows Content-Type, X-CSRF-TOKEN, etc.
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Required for X-CSRF-TOKEN
];
