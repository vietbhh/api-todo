<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TodoAddDefaultValue extends Migration
{
	public function up()
	{
		$field = [
			'status' => [
				'type' => 'TINYINT',
				'default' => TODO_WORK
			]
		];

		$this->forge->modifyColumn('todos', $field);
	}

	public function down()
	{
		$field = [
			'status' => [
				'type' => 'TINYINT'
			]
		];

		$this->forge->modifyColumn('todos', $field);
	}
}
