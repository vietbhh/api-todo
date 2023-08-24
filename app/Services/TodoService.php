<?php

namespace App\Services;

class TodoService
{
	protected $model;
	protected $userModel;

	public function __construct()
	{
		$this->model = model('TodoModel');
		$this->userModel = model('UserModel');
	}

	public function list($email): array
	{
		$userId = $this->userModel->where('email', $email)->first()['id'];

		return $this->model->where('user_id', $userId)->findAll();
	}

	public function create($email, $data): array
	{
		$userId = $this->userModel->where('email', $email)->first()['id'];
		$data['user_id'] = $userId;
		$newTodoId = $this->model->insert($data);

		return $this->model->find($newTodoId);
	}

	public function update($id, $data): bool
	{
		return $this->model->update($id, $data);
	}

	public function delete($id): bool
	{
		return $this->model->delete($id);
	}

	public function updateStatus($id): bool
	{
		$status = $this->model->find($id)['status'];

		return $this->model->update($id, [
			'status' => (int)$status === TODO_WORK ? TODO_DONE : TODO_WORK
		]);
	}
}