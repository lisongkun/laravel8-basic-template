<?php
/**
 * Webhook routes
 */


// 监听Coding仓库代码更新操作 /api/webhook/coding
Route::post('coding', [\App\Http\Controllers\Deploy\WebHookController::class, 'coding'])->name('coding');
