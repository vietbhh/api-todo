<?php

namespace App\Controllers;

use Services\TodoService;

class Todo extends BaseController
{
	public function list(): \CodeIgniter\HTTP\ResponseInterface
	{
		$todoModel = model('TodoModel');

		$todos = $todoModel->findAll();

		return $this->response->setStatusCode(200)->setJSON($todos);
	}

	public function create()
	{

	}

	//protected TodoService $todoService;

	//public function __construct(TodoService $todoService)
	//{
	//	$this->todoService = $todoService;
	//}

	//public function list(): \CodeIgniter\HTTP\ResponseInterface
	//{
	//	return $this->response->setStatusCode(200)->setJSON($this->todoService->listAll());
	//}
}
