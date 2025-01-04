<?php
// config/constants.php

return [
    // Application settings
    'APP_ENV' => $_ENV['APP_ENV'] ?? 'production',
    'APP_DEBUG' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'APP_URL' => $_ENV['APP_URL'] ?? 'http://localhost',

    // JWT settings (nếu có sử dụng)
    'JWT_SECRET' => $_ENV['JWT_SECRET'] ?? 'default_secret',
    'JWT_EXPIRATION' => intval($_ENV['JWT_EXPIRATION'] ?? 3600),

    // Default pagination settings
    'PAGINATION_LIMIT' => 10,

    // Upload settings
    'UPLOAD_DIR' => __DIR__ . '/../public/uploads',
    'ALLOWED_FILE_TYPES' => ['jpg', 'jpeg', 'png', 'gif'],
];