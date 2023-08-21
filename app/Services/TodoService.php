<?php

namespace App\Services;

class TodoService
{
	protected $todoModel;

	public function __construct()
	{
		$this->todoModel = model('TodoModel');
	}

	public function listAll()
	{
		return $this->todoModel->findAll();
	}
}