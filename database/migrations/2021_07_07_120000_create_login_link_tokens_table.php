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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_link_tokens');
    }
}
