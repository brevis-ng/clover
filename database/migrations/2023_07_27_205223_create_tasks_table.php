<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("chat_id");
            $table->string("name");
            $table->text("content");
            $table->string("image")->nullable();
            $table->string("cron")->default("0 */4 * * *");
            $table->timestamps();

            $table->foreign("chat_id")->references("id")->on("customers");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
