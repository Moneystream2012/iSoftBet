<?php

namespace App\Command;

use App\Entity\TransactionTotal;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TransactionTotalCommand extends Command
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TransactionRepository */
    private $transactionRepository;

    protected static $defaultName = 'TransactionTotal';

    public function __construct(
        EntityManagerInterface $em,
        TransactionRepository $transactionRepository,
        string $name = null
    )
    {
        $this->em = $em;
        $this->transactionRepository = $transactionRepository;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Transaction total by previous day');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $prevDate = date('Y-m-d', strtotime("-1 day"));

        $amount = $this->transactionRepository
            ->getTotalAmount(new \DateTime($prevDate));

        // store as formatted string - avoid implicit conversion of value
        $amount = sprintf("%01.2f", $amount);

        $totalEntity = (new TransactionTotal())
            ->setAmount($amount);

        $this->em->persist($totalEntity);
        $this->em->flush();

        $io->success("Total amount by {$prevDate} = {$totalEntity->getAmount()}");
    }
}
