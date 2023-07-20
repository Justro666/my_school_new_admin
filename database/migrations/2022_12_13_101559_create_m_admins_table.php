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
        Schema::create('m_admins', function (Blueprint $table) {
            $table->id();
            $table->string("name",255);
            $table->string("email",255)->unique();
            $table->string('password',255);
            $table->unsignedBigInteger("role_id");
            $table->integer("del_flg")->default(0);
            
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on("m_roles")
            ->onDelete('restrict')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_admins');
    }
};
