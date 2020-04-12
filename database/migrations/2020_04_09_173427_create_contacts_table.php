<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_contacts', function (Blueprint $table) {
            $table->id("contact_id");
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('role')->nullable();
            $table->foreignId('user_fk')->references('user_id')->on('tb_users');
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
        Schema::table('tb_contacts', function (Blueprint $table) {
            Schema::dropIfExists('tb_contacts');
        });
    }
}
