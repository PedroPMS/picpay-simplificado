<?php

namespace Picpay\User\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Picpay\User\Application\Create;
use Picpay\User\Application\Delete;
use Picpay\User\Application\Find;
use Picpay\User\Application\Get;
use Picpay\User\Application\Update;
use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Exceptions\UserTypeException;
use Picpay\User\Presentation\Http\Requests\CreateUserRequest;
use Picpay\User\Presentation\Http\Requests\UdpateUserRequest;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class UserController extends Controller
{
    public function index(Get $getAction): JsonResponse
    {
        $usersResponse = $getAction->getUsers();

        return response()->json($usersResponse);
    }

    /**
     * @throws UserNotFoundException
     */
    public function show(string $id, Find $findAction): JsonResponse
    {
        $userResponse = $findAction->findUser($id);

        return response()->json($userResponse);
    }

    /**
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
     * @throws UserNotFoundException
     */
    public function delete(string $id, Delete $deleteAction): Response
    {
        $deleteAction->deleteUser($id);

        return response()->noContent();
    }
}
