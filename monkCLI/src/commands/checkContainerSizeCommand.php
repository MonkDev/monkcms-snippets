<?php namespace monk\commands;

use Exception;
use OpenCloud\Rackspace;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use monk\commands\monkCommand;

class checkContainerSizeCommand extends monkCommand
{
    public function configure()
    {
        $this->setName('checkSize')
            ->setDescription('Check the size of a clients container (Just the portion that will be exported)')
            ->addArgument(
                'containerName',
                InputArgument::REQUIRED,
                'The name of the rackspace container you are trying to export'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $containerName = $input->getArgument('containerName');
                
        $this->message("<info>Starting to check the size of the uploads folder for {$containerName}</info>", $output);

        $client = $this->startRackspaceConnection();
        $container = $this->checkContainerSize($client, $containerName, $output);

        $this->message('Operation Complete.  You may close this window.', $output);
    }

    private function checkContainerSize($client, $containerName, OutputInterface $output)
    {
        try {
            // 2. Obtain an Object Store service object from rackspace.
            $objectStoreService = $client->objectStoreService(null, 'ORD');
            // Get the correct container
            $container = $objectStoreService->getContainer($containerName);
        } catch (Exception $e) {
            // 2. Obtain an Object Store service object from rackspace.
            $objectStoreService = $client->objectStoreService(null, 'DFW');
            // Get the correct container
            $container = $objectStoreService->getContainer($containerName);
        }

        $files = $container->ObjectList();
        $numberOfFiles = $container->getObjectCount();
        
        $progress = new ProgressBar($output, $numberOfFiles);
        $progress->setFormatDefinition('custom', '%percent:3s%% -- %current%/%max% [%bar%] ~ %message%');
        $progress->setFormat('custom');
        $progress->setMessage('Checking...');
        $progress->start();

        $fileSizes = array();
        ini_set('memory_limit', -1);
        
        while ($object = $files->Next()) {
            $cloudFile_name = $object->getName();
            $fileArr = explode('/', $cloudFile_name);
            $saveFile_Rackspace_name = array_pop($fileArr);
            $saveFile_nameArray = explode('_', $saveFile_Rackspace_name);
            $saveFile_name = array_pop($saveFile_nameArray);
            $this->progressOne($progress, $output);
            $progress->setMessage("Checking for {$saveFile_name}");

            //We only want files in the "uploaded" folder, everything else can be skipped
            if (!$fileArr) {
                continue;
            }
            if ($fileArr[0] !== 'uploaded') {
                continue;
            }

            // Get Specific File
            try {
                $file = $container->getObject($cloudFile_name);
                $fileSize = $object->getContentLength();
                $fileSizes[] = $fileSize;
            } catch (Exception $e) {
                continue;
            }
        }

        $totalSize = $this->formatBytes(array_sum($fileSizes));
        if ($totalSize === 'NAN') {
            $totalSize = '0 MB';
        }

        $progress->setMessage("Finished...");
        $progress->finish();

        $output->writeln('');
        $this->message("<info>Total file size is - {$totalSize}.</info>", $output, true);

        return $container;
    }

    /**
     * Take the bytes and convert into logical size
     * Code snippet taken from http://stackoverflow.com/a/2510540
     * @param $size
     * @param int $precision
     * @return string
     */
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', ' kb', ' MB', ' GB', ' TB');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }
}
