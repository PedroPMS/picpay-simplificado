<?php

namespace Tests\Unit\User\Domain\Services\Transaction;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;
use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Entities\User;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Enums\User\UserType;
use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\Transaction\TransactionStatusException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\User\UserTypeException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\Transaction\PayerHasEnoughBalanceForTransaction;
use Picpay\Domain\Services\Transaction\TransactionAuthorizer;
use Picpay\Domain\Services\Transaction\TransactionDebit;
use Picpay\Domain\Services\Transaction\TransactionFind;
use Picpay\Domain\Services\Transaction\TransactionUpdater;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Infrastructure\Models\TransactionModel;
use Picpay\Infrastructure\Models\UserModel;
use Tests\TestCase;

class TransactionValidatorTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }

    /**
     * @throws UserTypeException
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionStatusException
     */
    public function testItDispatchATransactionPendingWhenTransactionIsValidated()
    {
        // Arrange
        /** @var $transactionModel TransactionModel */
        $transactionModel = TransactionModel::factory()->make();
        $transaction = Transaction::fromPrimitives(
            $transactionModel->id,
            $transactionModel->payer_id,
            $transactionModel->payee_id,
            $transactionModel->value,
            $transactionModel->status,
        );

        /** @var $userModel UserModel */
        $userModel = UserModel::factory(['id' => $transactionModel->payer_id])->make();
        $user = User::fromPrimitives(
            $userModel->id,
            $userModel->name,
            $userModel->email,
            $userModel->cpf,
            $userModel->password,
            UserType::COMMON->value
        );

        $transactionFinderStub = m::mock(TransactionFind::class);
        $transactionUpdaterStub = m::mock(TransactionUpdater::class);
        $userFinderStub = m::mock(UserFind::class);
        $hasEnoughBalanceStub = m::mock(PayerHasEnoughBalanceForTransaction::class);
        $transactionAutorizerStub = m::mock(TransactionAuthorizer::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $hasEnoughBalanceStub->shouldReceive('checkPayerHasEnoughBalanceForTransaction')
            ->once()
            ->with(m::type(UserId::class), m::type(TransactionValue::class))
            ->andReturn();

        $transactionAutorizerStub->shouldReceive('isAutorized')
            ->once()
            ->andReturn(true);

        $transactionUpdaterStub->shouldReceive('updateTransactionStatus')
            ->once()
            ->with(m::type(TransactionId::class), TransactionStatus::DEBITED)
            ->andReturn();

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterStub,
            $transactionFinderStub,
            $transactionAutorizerStub,
            $hasEnoughBalanceStub
        );
        $validatorAction->validateTransaction($transaction->id);
    }

    /**
     * @throws UserTypeException
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionStatusException
     */
    public function testItDispatchATransactionRejectedEventWhenPayerIsShopkeeper()
    {
        // Arrange
        /** @var $transactionModel TransactionModel */
        $transactionModel = TransactionModel::factory()->make();
        $transaction = Transaction::fromPrimitives(
            $transactionModel->id,
            $transactionModel->payer_id,
            $transactionModel->payee_id,
            $transactionModel->value,
            $transactionModel->status,
        );

        /** @var $userModel UserModel */
        $userModel = UserModel::factory(['id' => $transactionModel->payer_id])->make();
        $user = User::fromPrimitives(
            $userModel->id,
            $userModel->name,
            $userModel->email,
            $userModel->cpf,
            $userModel->password,
            UserType::SHOPKEEPER->value
        );

        $transactionFinderStub = m::mock(TransactionFind::class);
        $transactionUpdaterDummy = m::mock(TransactionUpdater::class);
        $userFinderStub = m::mock(UserFind::class);
        $transactionAutorizerDummy = m::mock(TransactionAuthorizer::class);
        $hasEnoughBalanceDummy = m::mock(PayerHasEnoughBalanceForTransaction::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterDummy,
            $transactionFinderStub,
            $transactionAutorizerDummy,
            $hasEnoughBalanceDummy
        );
        $validatorAction->validateTransaction($transaction->id);
    }

    /**
     * @throws UserTypeException
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionStatusException
     */
    public function testItDispatchATransactionRejectedEventWhenPayerDoesntHaveEnoughBalance()
    {
        // Arrange
        /** @var $transactionModel TransactionModel */
        $transactionModel = TransactionModel::factory()->make();
        $transaction = Transaction::fromPrimitives(
            $transactionModel->id,
            $transactionModel->payer_id,
            $transactionModel->payee_id,
            $transactionModel->value,
            $transactionModel->status,
        );

        /** @var $userModel UserModel */
        $userModel = UserModel::factory(['id' => $transactionModel->payer_id])->make();
        $user = User::fromPrimitives(
            $userModel->id,
            $userModel->name,
            $userModel->email,
            $userModel->cpf,
            $userModel->password,
            UserType::COMMON->value
        );

        $transactionFinderStub = m::mock(TransactionFind::class);
        $transactionUpdaterDummy = m::mock(TransactionUpdater::class);
        $userFinderStub = m::mock(UserFind::class);
        $hasEnoughBalanceStub = m::mock(PayerHasEnoughBalanceForTransaction::class);
        $transactionAutorizerDummy = m::mock(TransactionAuthorizer::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $hasEnoughBalanceStub->shouldReceive('checkPayerHasEnoughBalanceForTransaction')
            ->once()
            ->with(m::type(UserId::class), m::type(TransactionValue::class))
            ->andThrows(PayerDoesntHaveEnoughBalanceException::payerDoesntHaveEnoughBalance());

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterDummy,
            $transactionFinderStub,
            $transactionAutorizerDummy,
            $hasEnoughBalanceStub
        );
        $validatorAction->validateTransaction($transaction->id);
    }

    /**
     * @throws UserTypeException
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionStatusException
     */
    public function testItDispatchATransactionRejectedEventWhenTransactionIsUnautorized()
    {
        // Arrange
        /** @var $transactionModel TransactionModel */
        $transactionModel = TransactionModel::factory()->make();
        $transaction = Transaction::fromPrimitives(
            $transactionModel->id,
            $transactionModel->payer_id,
            $transactionModel->payee_id,
            $transactionModel->value,
            $transactionModel->status,
        );

        /** @var $userModel UserModel */
        $userModel = UserModel::factory(['id' => $transactionModel->payer_id])->make();
        $user = User::fromPrimitives(
            $userModel->id,
            $userModel->name,
            $userModel->email,
            $userModel->cpf,
            $userModel->password,
            UserType::COMMON->value
        );

        $transactionFinderStub = m::mock(TransactionFind::class);
        $transactionUpdaterDummy = m::mock(TransactionUpdater::class);
        $userFinderStub = m::mock(UserFind::class);
        $hasEnoughBalanceStub = m::mock(PayerHasEnoughBalanceForTransaction::class);
        $transactionAutorizerStub = m::mock(TransactionAuthorizer::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $hasEnoughBalanceStub->shouldReceive('checkPayerHasEnoughBalanceForTransaction')
            ->once()
            ->with(m::type(UserId::class), m::type(TransactionValue::class))
            ->andReturn();

        $transactionAutorizerStub->shouldReceive('isAutorized')
            ->once()
            ->andReturn(false);

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterDummy,
            $transactionFinderStub,
            $transactionAutorizerStub,
            $hasEnoughBalanceStub
        );
        $validatorAction->validateTransaction($transaction->id);
    }
}
