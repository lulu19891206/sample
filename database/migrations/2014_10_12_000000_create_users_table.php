<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');//自增长ID
            $table->string('name');//用户名称
            $table->string('email')->unique();//唯一值
            $table->string('password',60);//最大长度是60
            $table->rememberToken();//创建一个remember_token 用于保存“记住我”的相关信息
            $table->timestamps();//创建一个created_at和updated_at 创建时间和更新时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
