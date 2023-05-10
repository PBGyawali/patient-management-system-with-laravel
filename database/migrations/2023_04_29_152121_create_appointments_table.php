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
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('appointment_id');
            $table->timestamp('appointment_start_time');
            $table->timestamp('appointment_end_time')->nullable();
            $table->string('patient_name');
            $table->integer('appointment_doctor_id');
            $table->integer('patient_id')->nullable();
            $table->string('appointment_source')->default('regular');
            $table->string('appointment_status')->default('inactive');
            $table->integer('appointment_department_id');
            $table->text('appointment_reason')->nullable();
            $table->integer('appointment_enter_by');
            $table->timestamp('created_at')->useCurrent();
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
        Schema::dropIfExists('appointments');
    }
};
