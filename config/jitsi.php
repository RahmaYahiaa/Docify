<?php

return [
    'app_id' => env('JAAS_APP_ID'),

    'private_key' => env('JAAS_PRIVATE_KEY') && file_exists(base_path(env('JAAS_PRIVATE_KEY')))
        ? file_get_contents(base_path(env('JAAS_PRIVATE_KEY')))
        : null,
    'api_key_id' => env('JAAS_API_KEY_ID'),

    'token_ttl_minutes' => env('JAAS_TOKEN_TTL', 15),

    'join_before_minutes' => env('JAAS_JOIN_BEFORE', 15),

    'join_after_minutes' => env('JAAS_JOIN_AFTER', 30),

    'orphan_threshold_minutes' => env('JAAS_ORPHAN_THRESHOLD', 60),
];
