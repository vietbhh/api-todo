<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTodo extends Migration
{
	public function up()
	{
		$this->forge->dropColumn('todos', 'title');

		$field = [
			'user_id' => [
				'type' => 'BIGINT'
			]
		];

		$this->forge->addColumn('todos', $field);
	}

	public function down()
	{
		$field = [
			'title' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			]
		];

		$this->forge->addColumn('todos', $field);

		$this->forge->dropColumn('todos', 'user_id');
	}
}
