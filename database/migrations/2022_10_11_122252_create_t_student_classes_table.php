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
        Schema::create('t_student_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->dateTime("start_join");
            $table->integer("paid_fees")->default(0);
            $table->integer("remain_fees")->default(0);
            $table->integer("refund")->default(0);
            $table->integer("del_flg")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('t_student_classes');
    }
};
