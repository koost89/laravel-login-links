<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginLinkTokensTable extends Migration
{
    public function up()
    {
        Schema::create('login_link_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('url');
            $table->integer('visits')->default(0);
            $table->integer('visits_allowed')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_link_tokens');
    }
}
