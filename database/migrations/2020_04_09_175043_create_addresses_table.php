<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_addresses', function (Blueprint $table) {
            $table->id("address_id");
            $table->string('zip_code');
            $table->string('street');
            $table->string('number');
            $table->string('neighborhood');
            $table->string('complement')->nullable();
            $table->string('city');
            $table->char('state', 2);
            $table->foreignId('contact_fk')->references('contact_id')->on('tb_contacts');
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
        Schema::dropIfExists('tb_addresses');
    }
}
