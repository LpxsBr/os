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
        Schema::create('service_orders', function (Blueprint $table){
            $table->id();
            $table->string('user_id');
            $table->string('equipament');
            $table->string('description');
            $table->string('area');
            $table->string('profile');
            $table->boolean('is_active');
            $table->boolean('is_preventive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
