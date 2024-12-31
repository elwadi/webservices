<?php

namespace App\Command;

use App\Entity\WpSite;
use App\Message\WpImportProduct;
use Automattic\WooCommerce\Client;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'wpai:import-product',
    description: 'Import products from wp site to local db',
)]
class WpaiImportProductCommand extends Command
{

    private $managerRegistry;
    private $bus;
    public function __construct(ManagerRegistry $managerRegistry,MessageBusInterface $bus)
    {
        parent::__construct();
        $this->managerRegistry = $managerRegistry;
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        //liste of all wp websites
        $em = $this->managerRegistry->getManager();
        $wpSites = $em->getRepository(WpSite::class)->findAll();

        foreach ($wpSites as $wpSite) {
            $io->writeln($wpSite->getWebsiteurl());

            $woocommerce = new Client(
                $wpSite->getWebsiteurl(),
                $wpSite->getCsKey(),
                $wpSite->getCsSecret(),
                [
                    'wp_api' => true,
                    'version' => 'wc/v3'
                ]
            );
    
            $data=$woocommerce->get('products');
            foreach ($data as $product) {
                $io->writeln($product->name);
                $this->bus->dispatch(new WpImportProduct($wpSite->getId(),$product->id,$product->name,$product->description));
            }
        }
        

        return Command::SUCCESS;
    }
}
