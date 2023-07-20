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
        Schema::create('t_mails', function (Blueprint $table) {
            $table->id();
            $table->string("m_title", 128);
            $table->string("m_description", 512);
            $table->unsignedBigInteger("class_id")->nullValue();
            $table->unsignedBigInteger("user_id")->nullValue();
            $table->integer("m_category");
            $table->integer("del_flg")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->foreign('class_id')->references('id')->on("m_classes")
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on("users")
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
        Schema::dropIfExists('t_mails');
    }
};
