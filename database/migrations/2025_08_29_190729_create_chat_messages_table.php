<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); // To identify unique chat sessions
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('message');
            $table->enum('sender_type', ['visitor', 'admin'])->default('visitor');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};
