<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id('branch_id');
            $table->string('facility_name')->nullable(false);
            $table->string('facility_address')->unique()->nullable();
            $table->string('facility_email')->unique()->nullable();
            $table->string('facility_contact_no',20)->nullable();
            $table->string('facility_timezone')->nullable();
            $table->string('facility_currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('facility_logo')->nullable();
            $table->string('secret_password')->nullable();
            $table->bigInteger('facility_target')->nullable(false)->default(0);
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
        Schema::dropIfExists('company_infos');
    }
}
