<?php

namespace Skel\Commands;

use Skel\Document;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDocsCommand extends Command
{
    protected static $defaultName = 'docs';

    protected static $defaultDoc = 'all';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create documentation for your project';

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
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $opt = [];
        $opt['licenseType'] = $input->getOption('license') ?? false;

        $arg = [];
        $arg['names'] = $input->getArgument('names') ?? self::$defaultDoc;
        if ( count($arg['names']) === 0 || $arg['names'][0] === self::$defaultDoc ) {
            $arg['names'] = ['readme', 'changelog', 'contributing'];
        }

        $document = new Document();
        foreach ($arg['names'] as $doc) {
            if ($doc == "license" && isset($opt['licenseType'])) {
                $doc .= '-' . $this->sanitizeLicenseName($opt['licenseType']);
            }

            $pathSource = $this->skeletonPath . '/'
                . $document->getSourceFileName($doc);
            $pathTo = $this->userProjectPath . '/'
                . $document->getDestinationFileName($doc);

            copy($pathSource, $pathTo);
        }

    }

    protected function sanitizeLicenseName($license)
    {
        $license = strtolower($license);

        return $license;
    }

    protected function configure()
    {
        $helpNames = 'specify the file name. choose from: ' .
                     'readme, changelog, contributing. ' .
                     self::$defaultDoc . ' is the default.';

        $this
            ->setDescription('Creates project docs.')
            ->setHelp('Generate boilerplate documentation for your project')
            ->addOption(
                'license',
                'l',
                InputOption::VALUE_REQUIRED,
                'type of software license',
                'mit'
            )
            ->addArgument(
                'names',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                $helpNames
            );
    }

    protected function info($message)
    {
        return '<info>' . $message . '</info>';
    }

    protected function link($url, $linkText = '')
    {
        if (empty($linkText)) {
            $linkText = $url;
        }
        return '<href=' . $url .'>' . $linkText . '</>';
    }

}
