<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id('patient_id');
            $table->string('patient_name')->nullable(false);
            $table->foreignId('patient_user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('patient_status')->default('active');
            $table->text('medical_history')->nullable();
            $table->smallInteger('patient_enter_by');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
