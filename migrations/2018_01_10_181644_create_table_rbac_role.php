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
        // 角色表
        Schema::create('rbac_role', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 角色名称
            $table->string('pinyin')->nullable(); // 拼音首字母
            $table->unsignedInteger('sort'); // 排序
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_role');
    }
};
