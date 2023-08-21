<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTodo extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true,
				'constraint' => 5
			],
			'username' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			],
			'email' => [
				'type' => 'VARCHAR',
				'constraint' => 150
			],
			'password' => [
				'type' => 'VARCHAR',
				'constraint' => 255
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
