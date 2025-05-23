<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TinyMCE API Key
    |--------------------------------------------------------------------------
    |
    | This is the API key for TinyMCE cloud. You can get one at
    | https://www.tiny.cloud/
    |
    */
    'api_key' => env('TINYMCE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | TinyMCE Default Configuration
    |--------------------------------------------------------------------------
    |
    | Default configuration options for TinyMCE
    |
    */
    'default_options' => [
        'plugins' => 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        'toolbar' => 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        'height' => 400,
    ],
]; 