<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hook', static function (Blueprint $table) {
            $table->id();
            $table->string('event')->comment('Hook name');
            $table->string('hash')->comment('SHA hash after commit');
            $table->jsonb('body')->comment('Gitlab request body');
            $table->bigInteger('event_id')->nullable()->comment('Some event id');
            $table->bigInteger('message_id')->nullable()->unsigned()->comment('Telegram message id');
            $table->jsonb('short_body')->nullable()->comment('Short body format');
            $table->jsonb('render')->nullable()->comment('Render template');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hook');
    }
};
