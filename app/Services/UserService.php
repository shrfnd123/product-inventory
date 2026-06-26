<?php

namespace App\Services;

use App\Constants\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Repositories\Interfaces\IUserRepository;
use Carbon\Carbon;

class UserService
{
    const MAX_ATTEMPTS = 10;
    const SECONDS_LOCKED = 3600;
    const ONE_HOUR = 1;

    public function __construct(
        private IUserRepository $userRepository
    ) {}

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Create or update user (clean version)
     */
    public function save(array $data): User
    {
        $payload = [
            'name' => $data['name'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        $user = $this->userRepository->updateOrCreate(
            ['id' => $data['user_id'] ?? null],
            $payload
        );

        return $user;
    }

    /**
     * Update password
     */
    public function updatePassword(int $userId, string $password): User
    {
        return $this->userRepository->update($userId, [
            'password' => Hash::make($password)
        ]);
    }

    /**
     * Rate limiting key
     */
    public function throttleKey(): string
    {
        return strtolower(request('email')) . '|' . request()->ip();
    }

    /**
     * Check failed attempts
     */
    public function isTooManyFailedAttempts(): bool
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey(),
            self::MAX_ATTEMPTS
        );
    }

    /**
     * Role check helper
     */
    public function isApprover(User $user): bool
    {
        return $user->hasAnyRole([
            'super-admin',
            'admin',
            'manager',
            'team-lead'
        ]);
    }
}