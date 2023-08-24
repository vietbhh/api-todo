<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\TodoService;

class Todo extends BaseController
{
	use ResponseTrait;

	protected TodoService $service;
	protected array $rules = [
		'content' => 'required|min_length[5]',
	];

	public function __construct()
	{
		$this->service = new TodoService();
	}

	public function list(): \CodeIgniter\HTTP\ResponseInterface
	{
		$email = $this->request->getHeaderLine('email');
		$list = $this->service->list($email);

		return $this->respond($list);
	}

	public function create(): ResponseInterface
	{
		if (!$this->validate($this->rules)) {
			return $this->failValidationErrors($this->validator->getErrors());
		}

		$email = $this->request->getHeaderLine('email');
		$data = $this->request->getPost();

		$newTodo = $this->service->create($email, $data);

		if (!$newTodo) {
			return $this->fail('failed');
		}

		return $this->respondCreated($newTodo);
	}

	public function update($id): ResponseInterface
	{
		if (!$this->validate($this->rules)) {
			return $this->failValidationErrors($this->validator->getErrors());
		}

		$updated = $this->service->update($id, $this->request->getJSON());

		if (!$updated) {
			return $this->fail('failed');
		}

		return $this->respondUpdated('updated');
	}

	public function delete($id): ResponseInterface
	{
		$isDeleted = $this->service->delete($id);

		if (!$isDeleted) {
			return $this->fail('failed');
		}

		return $this->respondDeleted('deleted');
	}

	public function updateStatus($id): ResponseInterface
	{
		$updated = $this->service->updateStatus($id);

		if (!$updated) {
			return $this->fail('failed');
		}

		return $this->respondUpdated('updated');

	}

}
