<?php

return [

    /**
     * Base API URL.
     */
    "base_url" => "/formoj/api/",

    /**
     * Disk and base path used for export XLS storage.
     */
    "export" => [
        "disk" => "local",
        "path" => "/formoj/tmp"
    ],

    /**
     * Disk and base path used for temporary uploads (upload fields).
     */
    "upload" => [
        "disk" => "local",
        "path" => "/formoj/tmp"
    ],

    /**
     * Disk and base path used for uploads storage (upload fields).
     */
    "storage" => [
        "disk" => "local",
        "path" => "/formoj/forms"
    ]
];