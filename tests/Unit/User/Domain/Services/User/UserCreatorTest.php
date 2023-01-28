<?php

namespace Tests\Unit\User\Domain\Services\User;

use Mockery as m;
use Picpay\Domain\Entities\User;
use Picpay\Domain\Events\User\UserWasPersisted;
use Picpay\Domain\Exceptions\User\UserAlreadyExistsException;
use Picpay\Domain\Exceptions\User\UserTypeException;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\Services\User\UserAlreadyExists;
use Picpay\Domain\Services\User\UserCreator;
use Picpay\Infrastructure\Models\UserModel;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
use Tests\TestCase;

class UserCreatorTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }

    /**
     * @throws UserTypeException
     * @throws UserAlreadyExistsException
     */
    public function testItCreateANewUserAndWallet()
    {
        // Arrange
        /** @var $userModel UserModel */
        $userModel = UserModel::factory()->make();
        $user = User::fromPrimitives(
            $userModel->id,
            $userModel->name,
            $userModel->email,
            $userModel->cpf,
            $userModel->password,
            $userModel->type
        );

        $repositoryStub = m::mock(UserRepository::class);
        $eventBusStub = m::mock(EventBusInterface::class);
        $checkUserExistsStub = m::mock(UserAlreadyExists::class);

        // Assert
        $checkUserExistsStub->shouldReceive('checkUserExists')
            ->once()
            ->with($user->email, $user->cpf)
            ->andReturn();

        $repositoryStub->shouldReceive('create')
            ->once()
            ->with(m::type(User::class))
            ->andReturn();

        $eventBusStub->shouldReceive('publish')
            ->once()
            ->with(m::type(UserWasPersisted::class))
            ->andReturn();

        // Act
        $createAction = new UserCreator($repositoryStub, $eventBusStub, $checkUserExistsStub);
        $createAction->createUser(
            $user->id,
            $user->name,
            $user->email,
            $user->cpf,
            $user->password,
            $user->type
        );
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws UserTypeException
     */
    public function testItTryToCreateANewUserWithInvalidMail()
    {
        // Arrange
        /** @var $userModel UserModel */
        $userModel = UserModel::factory()->make();
        $user = User::fromPrimitives(
            $userModel->id,
            $userModel->name,
            $userModel->email,
            $userModel->cpf,
            $userModel->password,
            $userModel->type
        );

        $repositoryStub = m::mock(UserRepository::class);
        $eventBusStub = m::mock(EventBusInterface::class);
        $checkUserExistsStub = m::mock(UserAlreadyExists::class);

        // Assert
        $this->expectException(UserAlreadyExistsException::class);

        $checkUserExistsStub->shouldReceive('checkUserExists')
            ->once()
            ->with($user->email, $user->cpf)
            ->andThrows(UserAlreadyExistsException::emailAlreadyExists());

        $repositoryStub->shouldNotReceive('create')
            ->never();

        $eventBusStub->shouldNotReceive('publish')
            ->never();

        // Act
        $createAction = new UserCreator($repositoryStub, $eventBusStub, $checkUserExistsStub);
        $createAction->createUser(
            $user->id,
            $user->name,
            $user->email,
            $user->cpf,
            $user->password,
            $user->type
        );
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws UserTypeException
     */
    public function testItTryToCreateANewUserWithInvalidCpf()
    {
        // Arrange
        /** @var $userModel UserModel */
        $userModel = UserModel::factory()->make();
        $user = User::fromPrimitives(
            $userModel->id,
            $userModel->name,
            $userModel->email,
            $userModel->cpf,
            $userModel->password,
            $userModel->type
        );

        $repositoryStub = m::mock(UserRepository::class);
        $eventBusStub = m::mock(EventBusInterface::class);
        $checkUserExistsStub = m::mock(UserAlreadyExists::class);

        // Assert
        $this->expectException(UserAlreadyExistsException::class);

        $checkUserExistsStub->shouldReceive('checkUserExists')
            ->once()
            ->with($user->email, $user->cpf)
            ->andThrows(UserAlreadyExistsException::cpfAlreadyExists());

        $repositoryStub->shouldNotReceive('create')
            ->never();

        $eventBusStub->shouldNotReceive('publish')
            ->never();

        // Act
        $createAction = new UserCreator($repositoryStub, $eventBusStub, $checkUserExistsStub);
        $createAction->createUser(
            $user->id,
            $user->name,
            $user->email,
            $user->cpf,
            $user->password,
            $user->type
        );
    }
}
