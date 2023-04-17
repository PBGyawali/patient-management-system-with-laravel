<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_histories', function (Blueprint $table) {
            $table->id('patient_history_id');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            //instead of using id using names so that hisory is not lost
            //even when the table is updated or deleted
            $table->string('patient_department')->nullable();
            $table->string('patient_name')->nullable(false);
            $table->string('patient_visit_doctor_name')->default('inactive');
            $table->timestamp('patient_in_time')->useCurrent();
            $table->timestamp('patient_out_time')->nullable();
            $table->string('patient_source')->default('regular');
            $table->string('patient_status')->default('inactive');
            $table->text('patient_reason_to_visit')->nullable();
            $table->text('patient_outing_remark')->nullable();
            $table->text('patient_remarks')->nullable();
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
        Schema::dropIfExists('patient_histories');
    }
}
