<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;


class CreateUser extends Migration
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
			'title' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			],
			'content' => [
				'type' => 'TEXT'
			],
			'created_at' => [
				'type' => 'TIMESTAMP',
				'default' => new RawSql('CURRENT_TIMESTAMP')
			],
			'updated_at' => [
				'type' => 'TIMESTAMP',
				'default' => new RawSql('CURRENT_TIMESTAMP')
			],
			'status' => [
				'type' => 'TINYINT'

			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('todos');
	}

	public function down()
	{
		$this->forge->dropTable('todos');
	}
}
