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
        Schema::create('t_lecture_notes', function (Blueprint $table) {
            $table->id();
            $table->string("l_name",128);
            $table->string("l_storage_link", 128);
            $table->string("l_storage_location", 16);
            $table->unsignedBigInteger("video_id");
            $table->integer("del_flg")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->foreign('video_id')->references('id')->on("m_videos")
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
        Schema::dropIfExists('t_lecture_notes');
    }
};
