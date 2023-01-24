<?php

namespace Picpay\User\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Picpay\User\Application\Controllers\Create;
use Picpay\User\Application\Controllers\Delete;
use Picpay\User\Application\Controllers\Find;
use Picpay\User\Application\Controllers\Get;
use Picpay\User\Application\Controllers\Update;
use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Exceptions\UserTypeException;
use Picpay\User\Presentation\Http\Requests\CreateUserRequest;
use Picpay\User\Presentation\Http\Requests\UdpateUserRequest;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class UserController extends Controller
{
    /**
     * List users
     *
     * List all users
     * @responseFile api/Users/List.json
     * @group Users
     */
    public function index(Get $getAction): JsonResponse
    {
        $usersResponse = $getAction->getUsers();

        return response()->json($usersResponse);
    }

    /**
     * Show user
     *
     * Show specific user
     * @group Users
     * @responseFile api/Users/Show.json
     * @throws UserNotFoundException
     */
    public function show(string $id, Find $findAction): JsonResponse
    {
        $userResponse = $findAction->findUser($id);

        return response()->json($userResponse);
    }

    /**
     * Create user
     *
     * Create a user
     * @group Users
     * @responseFile api/Users/Show.json
     * @throws UserTypeException
     * @throws UserAlreadyExistsException
     */
    public function store(CreateUserRequest $request, Create $createAction): JsonResponse
    {
        $userResponse = $createAction->createUser(
            $request->input('name'),
            $request->input('email'),
            $request->input('cpf'),
            $request->input('password'),
            $request->input('type'),
        );

        return response()->json($userResponse, StatusCode::HTTP_CREATED);
    }

    /**
     * Update user
     *
     * Update a user
     * @group Users
     * @responseFile api/Users/Show.json
     * @throws UserNotFoundException
     * @throws UserTypeException
     * @throws UserAlreadyExistsException
     */
    public function update(string $id, UdpateUserRequest $request, Update $updateAction): JsonResponse
    {
        $userResponse = $updateAction->updateUser(
            $id,
            $request->input('name'),
            $request->input('email'),
            $request->input('cpf'),
            $request->input('type'),
        );

        return response()->json($userResponse);
    }

    /**
     * Delete user
     *
     * Delete a user
     * @group Users
     * @response 204
     * @throws UserNotFoundException
     */
    public function delete(string $id, Delete $deleteAction): Response
    {
        $deleteAction->deleteUser($id);

        return response()->noContent();
    }
}
