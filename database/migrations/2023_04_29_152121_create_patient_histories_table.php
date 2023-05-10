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
        Schema::create('patient_histories', function (Blueprint $table) {
            $table->bigIncrements('patient_history_id');
            $table->string('patient_name');
            $table->string('patient_visit_doctor_name');
            $table->integer('patient_id')->nullable();
            $table->string('patient_source')->default('regular');
            $table->string('patient_status')->default('active');
            $table->integer('patient_department');
            $table->text('patient_outing_remark')->nullable();
            $table->text('patient_remarks')->nullable();
            $table->timestamp('patient_enter_time')->useCurrent();
            $table->timestamp('patient_out_time')->nullable();
            $table->text('patient_reason_to_visit')->nullable();
            $table->tinyInteger('patient_enter_by');
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
        Schema::dropIfExists('patient_histories');
    }
};
