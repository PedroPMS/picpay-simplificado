<?php

namespace Picpay\User\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Picpay\User\Application\Create;
use Picpay\User\Application\Find;
use Picpay\User\Application\Get;
use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Exceptions\UserTypeException;
use Picpay\User\Presentation\Requests\CreateUserRequest;

//use Picpay\User\Presentation\Requests\UdpateUserRequest;

class UserController extends Controller
{
    public function index(Get $getAction): JsonResponse
    {
        $usersResponse = $getAction->handle();

        return response()->json($usersResponse);
    }

    /**
     * @throws UserNotFoundException
     */
    public function show(string $id, Find $findAction): JsonResponse
    {
        $userResponse = $findAction->handle($id);

        return response()->json($userResponse);
    }

    /**
     * @throws UserTypeException
     * @throws UserAlreadyExistsException
     */
    public function store(CreateUserRequest $request, Create $createAction): JsonResponse
    {
        $userResponse = $createAction->handle(
            $request->input('name'),
            $request->input('email'),
            $request->input('cpf'),
            $request->input('password'),
            $request->input('type'),
        );

        return response()->json($userResponse);
    }

//    public function update(UdpateUserRequest $request, string $id): ResponseInterface
//    {
//        $command = new UpdateUserCommand(
//            $id,
//            $request->input('name'),
//            $request->input('email'),
//            $request->input('cpf'),
//        );
//
//        $this->commandBus->dispatch($command);
//
//        return $this->response->withStatus(200);
//    }
//
//    public function delete(string $id): ResponseInterface
//    {
//        $this->commandBus->dispatch(new DeleteUserByIdCommand($id));
//
//        return $this->response->withStatus(204);
//    }
}
