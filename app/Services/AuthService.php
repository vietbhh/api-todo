<?php

namespace App\Services;

use Firebase\JWT\JWT;

class AuthService
{
	protected $model;

	public function __construct()
	{
		$this->model = model('UserModel');
	}

	public function register($data)
	{
		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		$userId = $this->model->insert($data);

		return $this->model->find($userId);
	}

	public function verifyUser($data)
	{
		$user = $this->model->where('email', $data['email'])->first();

		if (!$user) {
			return false;
		}

		$passwordVerify = password_verify($data['password'], $user['password']);

		if (!$passwordVerify) {
			return false;
		}

		return $user;
	}

	public function tokenGenerate($user): string
	{
		$secretKey = getenv('JWT_SECRET');
		$now = time();
		$exp = $now + 60 * 60; // expire 1h

		$payload = [
			'time_start' => $now,
			'exp' => $exp,
			'email' => $user['email'],
			'username' => $user['username']
		];

		return JWT::encode($payload, $secretKey, 'HS256');
	}

}