<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('配置的键');
            $table->text('values')->nullable()->comment('配置的值');
            $table->string('description')->nullable()->comment('配置的描述');
            $table->tinyInteger('type')->default(1)->comment('1-字符串,2-JSON');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
