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
        $option = [];
        $option['extends'] = $input->getOption('extends');
        $option['package'] = $input->getOption('package');

        $arg = [];
        $arg['classname'] = $input->getArgument('classname') ?? null;

        $document = new Document();
        // Set the order of directories here to look for templates.
        $sourceDirs = $this->path->getTemplateDirs('php');

        $sourceDir = '';
        $templateFile = '';
        foreach($sourceDirs as $dir)  {
            $sourceDir = $dir;
            $templateFile = $this->getSourceFileName();
            $sourcePath = $dir . '/' . $templateFile;
            if (file_exists($sourceDir) && file_exists($sourcePath)) { 
                break;
            } 
        }

        $destinationPath = $this->path->getUserProjectDir()
            . '/' . $this->getDestinationFileName($arg['classname']);

        $io = new SymfonyStyle($input, $output);
        if ($io->isVerbose()) {
            $io->info(
                [
                    'Source Directories=' . print_r($sourceDirs, true),
                    'Source Path=' . $sourcePath,
                    'Destination Path=' . $destinationPath,
                ]
            );
        }

        $baseClassName = $option['extends'];
        $packageName = $option['package'];
        $className = $arg['classname'];

        $content = new Content();
        $loader = new \Twig\Loader\FilesystemLoader($sourceDir);
        $twig = new \Twig\Environment(
            $loader, [
            'debug' => true,
            ]
        );

        $userData = [
            'extends_suffix' => $baseClassName ? "extends $baseClassName" : '',
            'class_name' => $content->titleCase($className),
            'package_name' => $content->titleCase($packageName),
            'language' => "en-US",
            'date_year' => date('Y'),
        ]; 

        $fh = fopen($destinationPath, 'w');
        if (! $fh) {
            $io->error("Sorry, but the file '{$destinationPath}' cannot be written");
            return Command::FAILURE;
        }

        fwrite(
            $fh,
            $twig->render(
                $templateFile, 
                $userData 
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
                'The base class you are extending',
                ''
            )
            ->addOption(
                'package',
                null,
                InputOption::VALUE_REQUIRED,
                'The namespace containing your class',
                'Application'
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
