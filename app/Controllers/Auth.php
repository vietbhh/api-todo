<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
	protected $model;

	public function __construct()
	{
		$this->model = model('UserModel');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	}

	public function register(): \CodeIgniter\HTTP\ResponseInterface
	{
		$rules = [
			'username' => 'required|min_length[5]',
			'email' => 'required|min_length[8]|valid_email|is_unique[users.email]',
			'password' => 'required|min_length[5]',
			'confirm_password' => 'matches[password]'
		];

		if (!$this->validate($rules)) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)->setJSON($this->validator->getErrors());
		}

		$request = request();
		$data = $request->getPost();

		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

		$newUser = $this->model->insert($data);

		if (!$newUser) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON(['status' => 'failed']);
		}

		return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)->setJSON($this->model->find($newUser));
	}

	public function login(): ResponseInterface
	{
		$request = request();
		$data = $request->getPost();

		$user = $this->model->where('email', $data['email'])->first();

		if (!$user) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['message' => 'invalid email']);
		}

		$passwordVerify = password_verify($data['password'], $user['password']);

		if (!$passwordVerify) {
			return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['message' => 'invalid password']);
		}

		$secretKey = getenv('JWT_SECRET');
		$now = time();
		$exp = $now + 60;

		$payload = [
			'time_start' => $now,
			'exp' => $exp,
			'email' => $user['email'],
			'username' => $user['username']
		];

		$token = JWT::encode($payload, $secretKey, 'HS256');

		return $this->response->setStatusCode(ResponseInterface::HTTP_OK)->setJSON(['message' => 'Login successful', 'token' => $token]);

	}
}
