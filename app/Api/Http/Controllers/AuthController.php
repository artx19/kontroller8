<?php

namespace App\Api\Http\Controllers;

use App\Api\DTO\UserDTO;
use App\Api\Http\Requests\SignupRequest;
use App\Api\Http\Resources\UserResource;
use App\Api\Repositories\UserRepository;
use App\Api\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * API for users.
 */
class AuthController extends Controller
{
    /** @var UserService */
    private $service;

    /** @var UserRepository */
    private $repository;

    /**
     * @param UserService $service
     * @param UserRepository $repository
     */
    public function __construct(UserService $service, UserRepository $repository)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Signup for unauthorized users.
     *
     * @param SignupRequest $request
     * @return UserResource
     * @throws \App\Api\DTO\DTOException
     */
    public function signup(SignupRequest $request): UserResource
    {
        $dto = new UserDTO($request->all());
        $dto->setId(\Ulid::generate());

        $this->service->create($dto);

        $user = $this->repository->findById($dto->getId());
        $user->wasRecentlyCreated = true;

        return UserResource::make($user);
    }

    /**
     * Login user and create token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse [string] access_token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
}