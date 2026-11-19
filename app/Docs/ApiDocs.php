<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Perpus API',
    version: '1.0.0',
    description: 'Dokumentasi REST API Sistem Informasi Perpustakaan'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000/api',
    description: 'Local API Server'
)]
class ApiDocs
{
}