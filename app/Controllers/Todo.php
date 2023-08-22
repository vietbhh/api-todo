<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Services\TodoService;
use CodeIgniter\HTTP\Response;

class Todo extends BaseController
{
	protected $model;

	public function __construct()
	{
		$this->model = model('TodoModel');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	}

	public function list(): \CodeIgniter\HTTP\ResponseInterface
	{
		$todos = $this->model->findAll();

		return $this->response->setStatusCode(200)->setJSON($todos);
	}

	public function create(): ResponseInterface
	{
		$rules = [
			'content' => 'required|min_length[5]',
//			'status' => 'required'
		];


		if (!$this->validate($rules)) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)->setJSON(['status' => 'validate field']);
		}

		$request = request();
		$data = $request->getPost();

		$newIdTodo = $this->model->insert($data);


		if (!$newIdTodo) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON(['status' => 'failed']);
		}

		return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)->setJSON($this->model->find($newIdTodo));
	}

	public function update($id): ResponseInterface
	{
		$rules = [
			'content' => 'required|min_length[5]',
//			'status' => 'required'
		];

		if (!$this->validate($rules)) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)->setJSON(['status' => 'validate field']);
		}

		$updated = $this->model->update($id, $this->request->getJSON());

		if (!$updated) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON(['status' => 'failed']);
		}

		return $this->response->setStatusCode(ResponseInterface::HTTP_OK)->setJSON(['status' => 'failed']);
	}

	public function delete($id): ResponseInterface
	{
		$isDeleted = $this->model->delete($id);

		if (!$isDeleted) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['status' => 'failed']);
		}

		return $this->response->setStatusCode(ResponseInterface::HTTP_OK)->setJSON(['status' => 'success']);
	}

	public function updateStatus($id): ResponseInterface
	{
		$status = $this->model->find($id)['status'];

		$updated = $this->model->update($id, [
			'status' => (int)$status === TODO_WORK ? TODO_DONE : TODO_WORK
		]);

		if (!$updated) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['status' => 'failed']);
		}

		return $this->response->setStatusCode(ResponseInterface::HTTP_OK)->setJSON(['status' => 'success']);
	}

}
