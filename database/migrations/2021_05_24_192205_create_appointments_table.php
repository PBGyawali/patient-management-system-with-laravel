<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->timestamp('appointment_start_time');
            $table->timestamp('appointment_end_time')->nullable();
            $table->string('patient_name')->nullable(false);
            $table->foreignId('appointment_doctor_id')->constrained('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onUpdate('cascade')->onDelete('cascade');
            $table->string('appointment_source')->default('regular');
            $table->string('appointment_status')->default('inactive');
            $table->foreignId('appointment_department_id')->constrained('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->text('appointment_reason')->nullable();
            $table->smallInteger('appointment_enter_by');
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
        Schema::dropIfExists('appointments');
    }
}
