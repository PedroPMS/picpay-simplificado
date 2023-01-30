<?php

namespace Tests\Unit\User\Domain\Services\Transaction;

use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;
use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Entities\User;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Enums\User\UserType;
use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Transaction\ShopkeeperCantStartTransactionException;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\Transaction\TransactionStatusException;
use Picpay\Domain\Exceptions\Transaction\TransactionUnautorizedException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\User\UserTypeException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\Transaction\TransactionDebit;
use Picpay\Domain\Services\Transaction\TransactionFind;
use Picpay\Domain\Services\Transaction\TransactionUpdater;
use Picpay\Domain\Services\Transaction\TransactionValidate;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\Services\Wallet\WalletAmountDebit;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Infrastructure\Models\TransactionModel;
use Picpay\Infrastructure\Models\UserModel;
use Picpay\Shared\Domain\DbTransactionInterface;
use Tests\TestCase;

class TransactionDebitTest extends TestCase
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
    public function testItCommitATransactionDebit()
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

        $userFinderStub = m::mock(UserFind::class);
        $transactionUpdaterStub = m::mock(TransactionUpdater::class);
        $transactionFinderStub = m::mock(TransactionFind::class);
        $validateStub = m::mock(TransactionValidate::class);
        $walletDebitStub = m::mock(WalletAmountDebit::class);
        $transactionStub = m::mock(DbTransactionInterface::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $validateStub->shouldReceive('validateTransaction')
            ->once()
            ->with(m::type(User::class), m::type(Transaction::class))
            ->andReturn();

        $walletDebitStub->shouldReceive('debitWalletAmount')
            ->once()
            ->with(m::type(UserId::class), m::type(TransactionValue::class))
            ->andReturn();

        $transactionUpdaterStub->shouldReceive('updateTransactionStatus')
            ->once()
            ->with(m::type(TransactionId::class), m::type(TransactionStatus::class))
            ->andReturn();

        $transactionStub->shouldReceive('beginTransaction')
            ->once()
            ->andReturn();

        $transactionStub->shouldReceive('commit')
            ->once()
            ->andReturn();

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterStub,
            $transactionFinderStub,
            $validateStub,
            $walletDebitStub,
            $transactionStub
        );
        $validatorAction->debitTransaction($transaction->id);
    }

    /**
     * @throws UserTypeException
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionStatusException
     */
    public function testItRollbackATransactionDebit()
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

        $userFinderStub = m::mock(UserFind::class);
        $transactionUpdaterDummy = m::mock(TransactionUpdater::class);
        $transactionFinderStub = m::mock(TransactionFind::class);
        $validateStub = m::mock(TransactionValidate::class);
        $walletDebitStub = m::mock(WalletAmountDebit::class);
        $transactionDummy = m::mock(DbTransactionInterface::class);

        // Assert
        $this->expectException(Exception::class);

        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $validateStub->shouldReceive('validateTransaction')
            ->once()
            ->with(m::type(User::class), m::type(Transaction::class))
            ->andReturn();

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterDummy,
            $transactionFinderStub,
            $validateStub,
            $walletDebitStub,
            $transactionDummy
        );
        $validatorAction->debitTransaction($transaction->id);
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

        $userFinderStub = m::mock(UserFind::class);
        $transactionUpdaterStub = m::mock(TransactionUpdater::class);
        $transactionFinderStub = m::mock(TransactionFind::class);
        $validateStub = m::mock(TransactionValidate::class);
        $walletDebitDummy = m::mock(WalletAmountDebit::class);
        $transactionDummy = m::mock(DbTransactionInterface::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $validateStub->shouldReceive('validateTransaction')
            ->once()
            ->with(m::type(User::class), m::type(Transaction::class))
            ->andThrows(ShopkeeperCantStartTransactionException::class);

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterStub,
            $transactionFinderStub,
            $validateStub,
            $walletDebitDummy,
            $transactionDummy
        );
        $validatorAction->debitTransaction($transaction->id);
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

        $userFinderStub = m::mock(UserFind::class);
        $transactionUpdaterStub = m::mock(TransactionUpdater::class);
        $transactionFinderStub = m::mock(TransactionFind::class);
        $validateStub = m::mock(TransactionValidate::class);
        $walletDebitDummy = m::mock(WalletAmountDebit::class);
        $transactionDummy = m::mock(DbTransactionInterface::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $validateStub->shouldReceive('validateTransaction')
            ->once()
            ->with(m::type(User::class), m::type(Transaction::class))
            ->andThrows(PayerDoesntHaveEnoughBalanceException::class);

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterStub,
            $transactionFinderStub,
            $validateStub,
            $walletDebitDummy,
            $transactionDummy
        );
        $validatorAction->debitTransaction($transaction->id);
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

        $userFinderStub = m::mock(UserFind::class);
        $transactionUpdaterStub = m::mock(TransactionUpdater::class);
        $transactionFinderStub = m::mock(TransactionFind::class);
        $validateStub = m::mock(TransactionValidate::class);
        $walletDebitDummy = m::mock(WalletAmountDebit::class);
        $transactionDummy = m::mock(DbTransactionInterface::class);

        // Assert
        $transactionFinderStub->shouldReceive('findTransaction')
            ->once()
            ->with($transaction->id)
            ->andReturn($transaction);

        $userFinderStub->shouldReceive('findUser')
            ->once()
            ->with(m::type(UserId::class))
            ->andReturn($user);

        $validateStub->shouldReceive('validateTransaction')
            ->once()
            ->with(m::type(User::class), m::type(Transaction::class))
            ->andThrows(TransactionUnautorizedException::class);

        // Act
        $validatorAction = new TransactionDebit(
            $userFinderStub,
            $transactionUpdaterStub,
            $transactionFinderStub,
            $validateStub,
            $walletDebitDummy,
            $transactionDummy
        );
        $validatorAction->debitTransaction($transaction->id);
    }
}
