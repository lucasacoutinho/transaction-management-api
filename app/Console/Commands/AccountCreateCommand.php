<?php

namespace App\Console\Commands;

use App\Dtos\AccountDto;
use App\Models\Account;
use App\UseCases\Account\CreateAccountUseCase;
use Illuminate\Console\Command;

class AccountCreateCommand extends Command
{
    protected $signature = 'app:account-create';

    protected $description = 'Create a new account';

    public function handle(CreateAccountUseCase $createAccountUseCase)
    {
        $name = $this->ask('What is the account name of your choice?');
        $balance = $this->ask('How much should the balance be?', default: Account::DEFAULT_BALANCE);

        $bar = $this->output->createProgressBar(1);
        if ($this->confirm('Create account')) {
            $this->info('Creating account...');
            $bar->start();

            $accountDto = new AccountDto($name);
            $accountDto->setBalance($balance);

            $accountDto = $createAccountUseCase->execute($accountDto);
            $bar->advance();
        }
        $bar->finish();

        $this->newLine();
        $this->info(sprintf('Account with id: %s created', $accountDto->getAccount()->id));
    }
}
