<?php

use Illuminate\Support\Facades\Route;

Route::prefix('leads')->as('leads:')->group(
  base_path('routes/v1/leads/routes.php'),
);

