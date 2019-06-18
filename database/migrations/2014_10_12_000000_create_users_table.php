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
            $table->bigIncrements('id');
            $table->string('first_name');
			$table->string('last_name');
			$table->string('username')->unique();
            $table->string('email')->unique();
			$table->string('password');

			$table->tinyInteger('status')->default(0);
			/*
			 * -3	=	Member banned
			 * -2	=	Once a full member, no longer in good standing
			 * -1	=	Probationary member who was never approved (past probationary period)
			 * 0	=	Account created, but not yet email verified
			 * 1	=	Email verified, not yet membership admin approved
			 * 2	=	Probationary member in good standing, Membership admin approved
			 * 3	=	Full time member in good standing
			 * 4	=	Admin Member
			 * 5	=	Super Admin
			 */

			//Phone and Address
            $table->integer('phone');
			$table->integer('phone_ext');
            $table->string('address_1');
			$table->string('address_2')->default("");
			$table->string('address_3')->default("");
			$table->string('city');
			$table->string('state');
			$table->string('zip');

			//Who verified the member as eligible to be a probationary member and when
			$table->unsignedBigInteger('probation_verified_by')->nullable();
			$table->timestamp('probation_verified_at')->nullable();

			//Who verified the member as eligible to become a full member and when
			$table->unsignedBigInteger('membership_verified_by')->nullable();
			$table->timestamp('membership_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
