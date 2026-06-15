<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA; // Use native PHP 8 Attributes namespace

#[OA\Info(
    title: "SmartCMS Headless API",
    version: "1.0.0",
    description: "API headless para SmartCMS con autenticación JWT",
    contact: new OA\Contact(email: "admin@smartcms.com")
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
abstract class Controller
{
    // Base controller class
}



