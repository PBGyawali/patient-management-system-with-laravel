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
        Schema::create('company_infos', function (Blueprint $table) {
            $table->bigIncrements('branch_id');
            $table->string('facility_name')->nullable();
            $table->string('facility_address')->nullable();
            $table->string('facility_email')->nullable();
            $table->string('facility_contact_no')->nullable();
            $table->string('facility_timezone')->nullable();
            $table->string('facility_currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('facility_logo')->nullable();
            $table->string('secret_password')->nullable();
            $table->string('facility_target')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_infos');
    }
};
