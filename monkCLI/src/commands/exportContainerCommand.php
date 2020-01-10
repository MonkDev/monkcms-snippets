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
use monk\commands\monkCommand;

class exportContainerCommand extends monkCommand
{
    public function configure()
    {
        $this->setName('exportContainer')
            ->setDescription('Prepare an archive of a clients CloudFiles.')
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
     * @throws IOError
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $containerName = $input->getArgument('containerName');
        
        mkdir("files/{$containerName}");
        $savePath = "files/{$containerName}/";
        
        $this->message('<info>Starting...</info>', $output);

        $client = $this->startRackspaceConnection();
        $container = $this->downloadMedia($client, $containerName, $savePath, $output);
        $archiveName = $this->generateArchive($containerName, $output);
        //   @todo Fix the file upload feature
        //  $this->uploadArchive($container, $archiveName, $output);

        $this->message('Operation Complete.  You may close this window.', $output, true);
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
        $filesDownloaded = 0;
        $this->message('Number of Files - '. $numberOfFiles .'.', $output);
        $progress = new ProgressBar($output, $numberOfFiles);
        $progress->start();
        ini_set('memory_limit', -1);
        
        while ($object = $files->Next()) {
            $cloudFile_name = $object->getName();
            $fileArr = explode('/', $cloudFile_name);
            $saveFile_Rackspace_name = array_pop($fileArr);
            $saveFile_nameArray = explode('_', $saveFile_Rackspace_name);
            $saveFile_name = array_pop($saveFile_nameArray);
            $this->progressOne($progress, $output, true);
            $output->writeln('<info>Reading File - </info><comment>' . $saveFile_name . '</comment>');
            $fileTypeArray = explode('.', $saveFile_name);
            $fileType = array_pop($fileTypeArray);
            
            /*REMOVE THIS LINE IF YOU ONLY WANT specific Files
            
            // Define different filetypes and their various extensions
            $document = ['pdf','txt','rtf','doc','docx','odt'];
            $image = ['jpg','jpeg','png','gif','bmp','tiff','svg'];
            $audio = ['mp3','m4a','mpa','pcm','wav','aiff','aac','ogg','oga','wma','flac','alac'];
            $video = ['mp4','m4p','m4v','mov','wmv','avi','flv','qt','swf','avchd','asf','webm','mpg','mp2','mpeg','mpe','mpv'];
            $audioAndVideo = array_merge($audio, $video);
            $imageAndAudio = array_merge($image, $audio);
            $imageAndVideo = array_merge($image, $video);
            
            //We only want specific filetypes, everything else can be skipped.
            if (!in_array($fileType, $audioAndVideo)) { // Change the second parameter in in_array to the filetype you need
            	$this->message('File is not what we are looking for, skip it and move on to the next - <comment>'. $saveFile_name . '</comment>', $output);
                continue;
            }
            // REMOVE THIS LINE IF YOU ONLY WANT specific FILES */
            
            //We only want files in the "uploaded" folder, everything else can be skipped
            if (!$fileArr) {
                $this->message(
                  'File is not an original, skip it and move on to the next - <comment>' . $cloudFile_name . '</comment>',
                  $output
              );
                continue;
            }
            if ($fileArr[0] !== 'uploaded') {
                $this->message(
                 'File is not an original, skip it and move on to the next - <comment>' . $fileArr[0] . '</comment>',
                 $output
             );
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
                $this->message('The file failed to download. On to the next ¯\_(ツ)_/¯', $output);
                continue;
            }


            // Try to open file stream
            if (!$fp = @fopen($savePath . $saveFile_name, "wb")) {
                $this->message('<error>Could not open File: '. $savePath . $saveFile_name . 'for writing.</error>', $output);
//                throw new IOError('Could not open file: ' . $savePath . $saveFile_name . 'for writing.');
            }
            // Try to write the file to the directory
            if (fwrite($fp, $file->getContent()) === false) {
                $this->message(
                    '<error>Cannot write to file - ' . $savePath . $saveFile_name . '). Skipping File...</error>',
                    $output
                );
            } else {
                // File has been saved
                $this->message('<info>File successfully saved!</info>', $output);
                $filesDownloaded++;
            }
        }

        $progress->finish();
        $this->message('<info>Finished Downloading ' . $filesDownloaded . ' Files.</info>', $output, true);

        return $container;
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
           'name'        => $archiveName,
           'path'        => $archiveName,
           'metadata'    => array('Author' => 'Monk Development'),
           'concurrency' => 5,
           'partSize'    => 100 * Size::MB
       ));

        // 5. Initiate transfer
        $objectTransfer->upload();


        $this->message('<info>Upload Complete', $output);


        $cdn = $container->getCdn();
        $url = $cdn->getCdnSslUri() . '/' . $archiveName;
        $this->message('<info>You can download the archive by going to the following link:</info>', $output);
        $this->message('<comment>' . $url . '</comment>', $output);


        return $this;
    }
}
