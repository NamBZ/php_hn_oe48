<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone');
            $table->string('avatar')->nullable();
            $table->tinyInteger('role')->unsigned()->default(UserRole::User);
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('phone', 'avatar', 'role', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['phone', 'avatar', 'role', 'status']);
            });
        }
    }
}
