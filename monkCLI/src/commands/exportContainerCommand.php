<?php namespace monk\commands;

use Exception;
use OpenCloud\Common\Exceptions\IOError;
use OpenCloud\Common\Constants\Size;
use OpenCloud\Rackspace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class exportContainerCommand extends Command
{

    public function configure()
    {
        $this->setName('exportContainer')
            ->setDescription('Prepare an archive of a clients CloudFiles.')
            ->addArgument('containerName', InputArgument::REQUIRED,
                'The name of the rackspace container you are trying to export');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws IOError
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        $savePath = 'files/';
        $containerName = $input->getArgument('containerName');

        $this->message('<info>Starting...</info>', $output);

        $client = $this->startRackspaceConnection();
        $container = $this->downloadMedia($client, $containerName, $savePath, $output);
        $archiveName = $this->generateArchive($containerName, $output);
        //   @todo Fix the file upload feature
        // $this->uploadArchive($container, $archiveName, $output);

        $this->message('Operation Complete.  You may close this window.', $output, true);
    }

    private function message($string, OutputInterface $output, $addLineOnTop = false)
    {
        if ($addLineOnTop) {
            $output->writeln('');
        }
        $output->writeln($string);
        $output->writeln('');
    }

    private function startRackspaceConnection()
    {
        return new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => getenv('RACKSPACE_USERNAME'),
            'apiKey'   => getenv('RACKSPACE_API_KEY')
        ));
    }

    private function downloadMedia($client, $containerName, $savePath, OutputInterface $output)
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
        $this->message('Number of Files - '. $numberOfFiles .'.',$output);
        $progress = new ProgressBar($output, $numberOfFiles);
        $progress->start();
        ini_set('memory_limit', -1);
        while ($object = $files->Next()) {
            $cloudFile_name = $object->getName();
            $fileArr = explode('/', $cloudFile_name);
            $saveFile_name = array_pop($fileArr);
            $this->progressOne($progress, $output);
            $output->writeln('<info>Reading File - </info><comment>' . $saveFile_name . '</comment>');
            $fileType = array_pop(explode('.',$saveFile_name));
            // @todo eventually make this "only mp3" an opptional parameter to the Command
            // //We only want mp3 files, everything else can be skipped.
            // continue;
            // if ($fileType !== 'mp3') {
            //     $this->message('File is not an mp3, skip it and move on to the next - <comment>'. $saveFile_name . '</comment>', $output);
            //     continue;
            // }
            
            //We only want files in the "uploaded" folder, everything else can be skipped
            if ($fileArr[0] !== 'uploaded') {
                $this->message('File is not an original, skip it and move on to the next - <comment>' . $fileArr[0] . '</comment>',
                    $output);
                continue;
            }

            if (file_exists($savePath . '/' . $saveFile_name)) {
                $this->message('<comment>The file has already been downloaded.  On to the next!</comment>', $output);
                continue;
            }
            // Get Specific File
            try {
                $file = $container->getObject($cloudFile_name);
            } catch (Exception $e) {
                $this->message('The file failed to download. On to the next :(', $output);
                continue;
            }


            // Try to open file stream
            if (!$fp = @fopen($savePath . $saveFile_name, "wb")) {
                $this->message('<error>Could not open File: '. $savePath . $saveFile_name . 'for writing.</error>',$output);
//                throw new IOError('Could not open file: ' . $savePath . $saveFile_name . 'for writing.');
            }
            // Try to write the file to the directory
            if (fwrite($fp, $file->getContent()) === false) {
                $this->message('<error>Cannot write to file - ' . $savePath . $saveFile_name . '). Skipping File...</error>',
                    $output);
            } else {
                // File has been saved
                $this->message('<info>File successfully saved!</info>', $output);
            }
        }

        $progress->finish();
        $this->message('<info>Finished Downloading Files.</info>', $output, true);

        return $container;
    }

    private function progressOne(ProgressBar $progress, OutputInterface $output)
    {
        $progress->advance();
        $output->writeln('');
    }

    private function generateArchive($bucketName, OutputInterface $output)
    {
        $this->message('<comment>Beginning to archive.</comment>', $output);

        $archiveName = str_replace('.', '-', $bucketName) . '.zip';
        $rootPath = realpath('files');
        $zip = new ZipArchive();
        $zip->open($archiveName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        // Initialize empty "delete list"
        $filesToDelete = array();

        // Create recursive directory iterator
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);

                // Add current file to "delete list"
                // delete it later cause ZipArchive create archive only after calling close function and ZipArchive lock files until archive created)
                $filesToDelete[] = $filePath;
            }
        }
        // Zip archive will be created only after closing object
        $zip->close();

       // Delete all files from "delete list"
       foreach ($filesToDelete as $file) {
           unlink($file);
       }
        $this->message('<info>Archive Finished - ' . $archiveName . '</info>', $output);

        return $archiveName;
    }

   private function uploadArchive($container, $archiveName, OutputInterface $output)
   {
       $this->message('<info>Uploading Archive - Please Do Not Close The Window!</info>', $output, true);

       // 4. Configure
       $objectTransfer = $container->setupObjectTransfer(array(
           'name'        => 'exportOfMedia.zip',
           'path'        => $archiveName,
           'metadata'    => array('Author' => 'Monk Development'),
           'concurrency' => 5,
           'partSize'    => 1 * Size::GB
       ));

       // 5. Initiate transfer
       $objectTransfer->upload();


       $this->message('<info>Upload Complete', $output);

       
       $cdn = $container->getCdn();
       $url = $cdn->getCdnSslUri() . '/exportOfMedia.zip';
       $this->message('<info>You can download the archive by going to the following link:</info>', $output);
       $this->message('<comment>' . $url . '</comment>', $output);


       return $this;
   }
}