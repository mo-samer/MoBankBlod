<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDonationRequestsTable extends Migration {

	public function up()
	{
		Schema::create('donation_requests', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('patien_name');
			$table->string('patien_phone');
			$table->integer('city_id')->unsigned();
			$table->string('hospital_name');
			$table->integer('blood_type_id')->unsigned();
			$table->integer('patien_age');
			$table->integer('bages_count');
			$table->string('hospital_adress');
			$table->decimal('latitude', 10,8);
			$table->decimal('longtude', 10,8);
			$table->integer('client_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('donation_requests');
	}
}