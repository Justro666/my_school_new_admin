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
        Schema::create('m_classes', function (Blueprint $table) {
            $table->id();
            $table->string("c_name",128);
            $table->string("c_description", 512);
            $table->string("c_start_date", 10);
            $table->string("c_end_date", 10);
            $table->string("c_date", 7);
            $table->string("c_start_time", 5);
            $table->string("c_end_time", 128);
            $table->integer("c_fees");
            $table->unsignedBigInteger("instructor_id");
            $table->string("c_profile", 256);
            $table->unsignedBigInteger("category_id");
            $table->integer("del_flg")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->foreign('instructor_id')->references('id')->on("m_instructors")
            ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on("m_categories")
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
        Schema::dropIfExists('m_classes');
    }
};
