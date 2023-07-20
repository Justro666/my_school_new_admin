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
        Schema::create('m_sitemaster_publics', function (Blueprint $table) {
            $table->id();
            $table->string("logo", 128);
            $table->string("sitename", 128);
            $table->string("favicon", 128);
            $table->string("facebook_link", 128);
            $table->string("youtube_link1", 128);
            $table->string("youtube_link2", 128);
            $table->string("phones", 128);
            $table->string("copyright", 128);
            $table->integer("del_flg")->default(0);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
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
        Schema::dropIfExists('m_sitemaster_publics');
    }
};
