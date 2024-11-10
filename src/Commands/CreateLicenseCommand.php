<?php

namespace Skel\Commands;

use Skel\License;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'license',
    description: 'Creates a new user.',
    hidden: false,
    aliases: ['app:add-user']
)]
class CreateLicenseCommand extends Command
{
    protected static $defaultName = 'license';

    protected static $defaultLicense = 'mit';

    protected $licenses = ['mit', 'gpl-2'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a license for your project';

    protected $skeletonPath = '';

    protected $userProjectPath = '';

    /**
     * Create a new command instance.
     *
     * @param string $path the directory of the skeleton project
     *
     * @return void
     */
    public function __construct(string $path, string $userProjectPath)
    {
        parent::__construct();

        $this->skeletonPath = $path;
        $this->userProjectPath = $userProjectPath;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $arg = [];
        $arg['name'] = $input->getArgument('name') ?? self::$defaultLicense;

        $license = new License();

        $pathSource = $this->skeletonPath . '/'
            . $license->getSourceFileName($arg['name']);
        $pathTo = $this->userProjectPath . '/'
            . $license->getDestinationFileName($arg['name']);

        copy($pathSource, $pathTo);
    }


    protected function configure()
    {
        $nameHelp = 'Pick your license. Choose from: '
            . implode(', ', $this->licenses);
        $this
            ->setDescription('Creates project license.')
            ->setHelp('Generate a license for your project')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Which license do you want?',
                self::$defaultLicense
            );
    }

}
