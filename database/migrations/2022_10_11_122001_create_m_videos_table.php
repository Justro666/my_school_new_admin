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
        Schema::create('m_videos', function (Blueprint $table) {
            $table->id();
            $table->string("v_name",128);
            $table->string("v_descriprion", 256);
            $table->unsignedBigInteger("class_id");
            $table->dateTime("v_date");
            $table->string("v_storage_link",256);
            $table->string("v_storage_location",16);
            $table->integer("del_flg")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->foreign('class_id')->references('id')->on("m_classes")
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
        Schema::dropIfExists('m_videos');
    }
};
