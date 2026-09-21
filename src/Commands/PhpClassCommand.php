<?php

namespace Skel\Commands;

use AndrewWoods\ChicagoStyle\Content;
use Skel\Document;
use Skel\Path;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnexpectedValueException;

#[AsCommand(
    name: 'php:class',
    description: 'Create a PHP class',
    hidden: false
)]
class PhpClassCommand extends Command
{
    protected static $defaultName = 'php';

    protected Path $path;


    /*==========================================================================
     *   Magic Functions
     *==========================================================================
     */

    /**
     * Create a new command instance.
     *
     * @param string $path the directory of the skeleton project
     *
     * @return void
     */
    public function __construct(Path $path)
    {
        parent::__construct();

        $this->path = $path;
    }

    /*==========================================================================
     *   Public Functions
     *==========================================================================
     */

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $defaultBaseClass = '';
        $defaultPackage = 'Application';

        $option = [];
        $option['extends'] = $input->getOption('extends') ?? $defaultBaseClass;
        $option['package'] = $input->getOption('package') ?? $defaultPackage;

        $arg = [];
        $arg['classname'] = $input->getArgument('classname') ?? null;

        $document = new Document();

        $pathSourceDir = $this->path->getSkeletonTemplatePath('php');
        $pathSource = $pathSourceDir 
            . '/' . $this->getSourceFileName();

        $pathSourceUserDir = $this->path->getTemplatePath();
        $pathSourceUser = $pathSourceUserDir
            . '/' . $this->getSourceFileName();

        if (file_exists($pathSourceUser)) { 
            $pathSource = $pathSourceUser;
            $pathSourceDir = $pathSourceUserDir;
        } 

        $pathTo = $this->path->getUserPath()
            . '/' . $this->getDestinationFileName($arg['classname']);

        $io = new SymfonyStyle($input, $output);

        $baseClassName = $option['extends'];
        $packageName = $option['package'];
        $className = $arg['classname'];

        $content = new Content();
        $loader = new \Twig\Loader\FilesystemLoader($pathSourceDir);
        $twig = new \Twig\Environment(
            $loader, [
            'debug' => true,
            ]
        );

        $formatOpalDate = 'Y M d D';
        $formatOpalDateTime = 'Y M d D H:i';
        $dayInSeconds = 86_400;
        $dateYear = date('Y');
        $dateIsoDate = date('Y-m-d');
        $dateIsoDateTime = date('Y-m-dTH:i');
        $dateIsoTimeStamp = date('Y-m-dTH:i:sP');
        $dateToday = date($formatOpalDate);
        $dateDue = date($formatOpalDate, \time() + (10 * $dayInSeconds));
        $nowDate = date($formatOpalDate);
        $nowDateTime = date($formatOpalDateTime);

        $fh = fopen($pathTo, 'w');
        if (! $fh) {
            $output->writeln("Sorry, but the file '{$pathTo}' cannot be written");
            return Command::FAILURE;
        }
        $templateFile = $this->getSourceFileName();
        fwrite(
            $fh,
            $twig->render(
                $templateFile, [
                'extends_suffix' => $baseClassName ? "extends $baseClassName" : '',
                'class_name' => $content->titleCase($className),
                'package_name' => $content->titleCase($packageName),
                'language' => "en-US",
                'iso_date' => $dateIsoDate,
                'iso_datetime' => $dateIsoDateTime,
                'iso_timestamp' => $dateIsoTimeStamp,
                'date_created' =>  $dateToday,
                'date_due' =>  $dateDue,
                'now_date' =>  $nowDate,
                'now_datetime' =>  $nowDateTime,
                ]
            )
        );

        return Command::SUCCESS;
    }

    /*==========================================================================
     *   Protected Functions
     *==========================================================================
     */
    protected function configure()
    {
        $this
            ->setHelp('Create a PHP class.')
            ->addOption(
                'extends',
                null,
                InputOption::VALUE_REQUIRED,
                'The base class you are extending'
            )
            ->addOption(
                'package',
                null,
                InputOption::VALUE_REQUIRED,
                'The namespace containing your class'
            )
            ->addArgument(
                'classname',
                InputArgument::REQUIRED,
                'The name of the class you are creating'
            );
    }

    protected function getSourceFileName()
    {
        return 'php-class.php';
    }

    protected function getDestinationFileName($className)
    {
        return $className . '.php';
    }

}
