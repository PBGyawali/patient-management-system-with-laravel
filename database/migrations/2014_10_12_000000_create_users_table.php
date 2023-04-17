<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable(false);
            $table->string('name')->nullable();
            $table->string('email')->unique()->index()->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('gender', ['male','female','other','not mentioned',])->default('not mentioned');
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('profile')->unique();
            $table->string('user_status')->default('inactive');
            $table->string('contact_no',20)->nullable();
            $table->enum('user_type', ['master','owner','admin','editor','doctor','patient','user'])->default('user');
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('created_at')->nullable()->useCurrent();
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
}
