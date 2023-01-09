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
        $data = [
            ['id' => '1', 'name' => 'Хобби'],
            ['id' => '2', 'name' => 'Упражнения'],
            ['id' => '3', 'name' => 'Работа'],
            ['id' => '4', 'name' => 'Другое']
        ];
        DB::table('types')->insert($data);    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('types')->
        whereBetween('id', [1, 4])->
        delete();
    }
};
