<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Included options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    |
    */
    'driver' => env('IMAGE_DRIVER', 'gd'),

    /*
    |--------------------------------------------------------------------------
    | Image Quality
    |--------------------------------------------------------------------------
    | Quality for WebP compression (0-100)
    */
    'quality' => env('IMAGE_QUALITY', 85),

    /*
    |--------------------------------------------------------------------------
    | Maximum Image Dimensions
    |--------------------------------------------------------------------------
    | Maximum width and height for uploaded images
    */
    'max_width' => env('IMAGE_MAX_WIDTH', 1200),
    'max_height' => env('IMAGE_MAX_HEIGHT', 800),

    /*
    |--------------------------------------------------------------------------
    | Article Image Dimensions
    |--------------------------------------------------------------------------
    | Specific dimensions for blog article images
    */
    'article_max_width' => env('ARTICLE_IMAGE_MAX_WIDTH', 1200),
    'article_max_height' => env('ARTICLE_IMAGE_MAX_HEIGHT', 800),

    /*
    |--------------------------------------------------------------------------
    | Property Image Dimensions
    |--------------------------------------------------------------------------
    | Specific dimensions for property images
    */
    'property_max_width' => env('PROPERTY_IMAGE_MAX_WIDTH', 1200),
    'property_max_height' => env('PROPERTY_IMAGE_MAX_HEIGHT', 800),
];
