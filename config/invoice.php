<?php

return [
    "api_key" => env('INVOICE_API_KEY', "api_key"),
    "login" => env('INVOICE_LOGIN', "company_id"),
    "default_terminal_name" => env('INVOICE_TERMINAL_NAME', "terminal_name"),
    "terminal_id" => env('INVOICE_TERMINAL_ID', "terminal_id"),
];
