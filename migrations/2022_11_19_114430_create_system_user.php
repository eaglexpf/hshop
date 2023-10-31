<?php

declare(strict_types=1);
/**
 * This file is part of Hapi.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateSystemUser extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_user', function (Blueprint $table) {
            $table->bigIncrements('user_id')->comment('用户ID');
            $table->string('password')->comment('密码');
            $table->string('password_hash')->comment('密码密钥');
            $table->string('user_name', 100)->comment('用户昵称');
            $table->string('avatar_url')->nullable()->comment('用户头像');
            $table->enum('user_status', ['success', 'disabled'])->default('success')->comment('用户状态 success:正常 disabled:禁用');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->index('user_status', 'index_user_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_user');
    }
}
