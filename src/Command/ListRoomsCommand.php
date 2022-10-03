<?php

namespace App\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Room;
use App\Repository\RoomRepository;

class ListRoomsCommand extends Command
{
    
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->roomRepository = $container->get('doctrine')->getManager()->getRepository(Room::class);
    }
    
    protected static $defaultName = 'app:List-rooms';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rooms = $this->roomRepository->findAll();
        if(!$rooms) {
            $output->writeln('<comment>no rooms found<comment>');
            exit(1);
        }
        
        foreach($rooms as $room)
        {
            $output->writeln($room);
        }
        
        return 0;
    }
    
}
