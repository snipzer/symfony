<?php

namespace CustomerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerWatchBalanceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('customer:watch-balance')
            ->setDescription('...')
            ->addArgument('balance', InputArgument::REQUIRED, 'Balance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('balance');

        $customers = $this->getContainer()->get("customer.balance_watcher")->getCustomerWithBalanceLowerThan($argument);

        $i = 0;
        foreach($customers as $customer)
        {
            $str = "Customer n°$i: FirstName: ".$customer->getFirstName().", LastName: ".$customer->getLastName().", Birthday: ".$customer->getBirthday()->format("d-m-Y").", Balance: ".$customer->getBalance();
            $output->writeln($str);
            $i++;
        }
    }

}
