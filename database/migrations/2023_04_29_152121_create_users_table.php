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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('name')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'not mentioned'])->default('not mentioned');
            $table->date('birthdate')->nullable();
            $table->string('email')->unique();
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('profile')->nullable();
            $table->enum('user_status', ['active', 'inactive'])->default('inactive');
            $table->enum('user_type', ['master', 'owner', 'admin', 'editor', 'doctor', 'patient', 'user'])->default('user');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
