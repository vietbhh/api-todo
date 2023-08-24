<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\AuthService;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
	use ResponseTrait;

	protected AuthService $service;
	protected array $rules = [
		'username' => 'required|min_length[5]',
		'email' => 'required|min_length[8]|valid_email|is_unique[users.email]',
		'password' => 'required|min_length[5]',
		'confirm_password' => 'matches[password]'
	];

	public function __construct()
	{
		$this->service = new AuthService();
	}

	public function register(): \CodeIgniter\HTTP\ResponseInterface
	{
		if (!$this->validate($this->rules)) {
			return $this->failValidationErrors($this->validator->getErrors());
		}

		$newUser = $this->service->register($this->request->getPost());

		if (!$newUser) {
			return $this->fail('failed');
		}

		return $this->respondCreated($newUser);
	}

	public function login(): ResponseInterface
	{
		$user = $this->service->verifyUser($this->request->getPost());

		if (!$user) {
			return $this->fail('Invalid email or password');
		}

		$token = $this->service->tokenGenerate($user);

		return $this->respond(['message' => 'Login successful', 'token' => $token]);
	}
}
