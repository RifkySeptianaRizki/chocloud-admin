<?php
// api/index.php

// --- PAKSA BATAS UKURAN UPLOAD SECARA LANGSUNG DI PHP INI ---
ini_set('upload_max_filesize', '20M');
ini_set('post_max_size', '25M');
// --- AKHIR PAKSA BATAS UKURAN UPLOAD ---

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the web site.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());