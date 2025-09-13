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
        Schema::create('company_report', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('country')->default('China');
            $table->string('orderId');
            $table->string('orgId');
            $table->string('status')->default('pending');
            $table->timestamp('date_time')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_report');
    }
};
