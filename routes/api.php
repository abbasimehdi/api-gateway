<?php

use App\Http\Controllers\Api\GatewayController;

// routes/api.php

Route::any('/gateway/{service}/{path?}', [GatewayController::class, 'proxy'])
    ->where('path', '.*');   // allow slashes in path