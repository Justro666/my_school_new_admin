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
        Schema::create('m_privacy_policies', function (Blueprint $table) {
            $table->id();
            $table->string("p_title",128);
            $table->string("p_description", 1024);
            $table->string("p_photo", 128);
            $table->unsignedBigInteger("category_id");
            $table->integer("del_flg")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('m_privacy_policies');
    }
};
