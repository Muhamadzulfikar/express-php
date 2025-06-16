<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

return new class {
    public function up()
    {
        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        echo "Table 'users' created.\n";
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('users');
        echo "Table 'users' dropped.\n";
    }
};
