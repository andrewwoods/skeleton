<?php

namespace Skel\Commands;

use AndrewWoods\ChicagoStyle\Content;
use Skel\Path;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

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
        $option['template'] = $input->getOption('template');

        $arg = [];
        $arg['classname'] = $input->getArgument('classname') ?? null;

        $io = new SymfonyStyle($input, $output);

        $sourceDirs = $this->path->getTemplateDirs('php');
        $configFile = $this->hasConfigFile();

        $config = null;
        if ($io->isVerbose()) {
            if ($configFile === false ) {
                $io->warning(
                    [
                        'Config file is not available',
                        'Create a config file in one of these locations:',
                         implode("\n", $this->path->getConfigFiles()),
                    ]
                );
            } else {
                $io->success(
                  [
                    'Config file found',
                    $configFile
                  ]
                );
            }
        }

        try {
            $config = Yaml::parseFile($configFile);
        } catch (ParseException $e) {
            $output->writeln('<error>Unable to parse YAML: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
        
        $sourceDir = '';
        $templateFile = '';
        $userSourceDir = '';
        $userSourcePath = '';
        $userTemplateFile = '';
        if (isset($config['templates']['php-class'][ $option['template'] ])) {
            $userTemplateFile = $config['templates']['php-class'][ $option['template'] ] ;
        }

        $defaultTemplateFile = $this->getSourceFileName($option['template']);

        foreach($sourceDirs as $dir)  {
            $sourceDir = '';
            $templateFile = '';
            $userSourcePath = "{$dir}/{$userTemplateFile}";
            if (file_exists($userSourcePath)) {
                $sourceDir = dirname($userSourcePath);
                $templateFile = basename($userSourcePath);
                break;
            }

            $defaultSourcePath = "{$dir}/{$defaultTemplateFile}";
            if (file_exists($defaultSourcePath)) {
                $sourceDir = dirname($defaultSourcePath);
                $templateFile = basename($defaultSourcePath);
                break;
            } 
        }

        if ($io->isVerbose()) {
            $io->success(
                [
                    "sourceDir={$sourceDir}",
                    "templateFile={$templateFile}",
                ]
            );
        }

        $destinationPath = $this->path->getUserProjectDir()
            . '/' . $this->getDestinationFileName($arg['classname']);

        if ($io->isVeryVerbose()) {
            $io->note(
                [
                    'Config=' . print_r($config, true),
                    'Config File=' . $configFile,
                    'Config Files=' . print_r($this->path->getConfigFiles(), true),
                    'option[template]=' . $option['template'],
                    'Template File=' . $templateFile,
                    'Source Directories=' . print_r($sourceDirs, true),
                    'Source Dir=' . $sourceDir,
                    'Default Source Path=' . $defaultSourcePath,
                    'User Source Dir=' . $userSourceDir,
                    'User Source Path=' . $userSourcePath,
                    'User Template File=' . $userTemplateFile,
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
            'given' => $config['user']['given'] ?? '',
            'surname' => $config['user']['surname'] ?? '',
            'email' => $config['user']['email'] ?? '',
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
            ->addOption(
                'template',
                null,
                InputOption::VALUE_REQUIRED,
                'The template determining the style of PHP class you want',
                'default'
            )
            ->addArgument(
                'classname',
                InputArgument::REQUIRED,
                'The name of the class you are creating'
            );
    }

    protected function getSourceFileName($template)
    {
        return match ($template) {
            'phpunit' => 'phpunit-class.php',
            'wordpress' => 'wordpress-class.php',
            'default' => 'php-class.php',
        };
    }

    protected function getDestinationFileName($className)
    {
        return $className . '.php';
    }

    protected function hasConfigFile()
    {
        foreach($this->path->getConfigFiles() as $configFile)  {
            if (file_exists($configFile)) { 
                return $configFile;
            } 
        }

        return false;
    }

}
