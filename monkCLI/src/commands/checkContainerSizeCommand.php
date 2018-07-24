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
            $fileTypeArray = explode('.', $saveFile_name);
            $fileType = array_pop($fileTypeArray);


            //We only want files in the "uploaded" folder, everything else can be skipped
            if (!$fileArr) {
                continue;
            }
            if ($fileArr[0] !== 'uploaded') {
                continue;
            }
            
            // Define different filetypes and their various extensions
            $document = ['pdf','txt','rtf','doc','docx','odt'];
            $image = ['jpg','jpeg','png','gif','bmp','tiff','svg'];
            $audio = ['mp3','m4a','mpa','pcm','wav','aiff','aac','ogg','oga','wma','flac','alac'];
            $video = ['mp4','m4p','m4v','mov','wmv','avi','flv','qt','swf','avchd','asf','webm','mpg','mp2','mpeg','mpe','mpv'];
            $audioAndVideo = array_merge($audio, $video);
            $imageAndAudio = array_merge($image, $audio);
            $imageAndVideo = array_merge($image, $video);


            $fileSize = $object->getContentLength();

            // Get document files size total
            if (in_array($fileType, $document)) {
                $documentSizes[] = $fileSize;
            }

            // Get image files size total
            if (in_array($fileType, $image)) {
                $imageSizes[] = $fileSize;
            }

            // Get audio files size total
            if (in_array($fileType, $audio)) {
                $audioSizes[] = $fileSize;
            }

            // Get video files size total
            if (in_array($fileType, $video)) {
                $videoSizes[] = $fileSize;
            }

            // Get total file sizes
            try {
                $fileSizes[] = $fileSize;
            } catch (Exception $e) {
                continue;
            }

        }

        $documentSize = $this->formatBytes(array_sum($documentSizes));
        if ($documentSizes === 'NAN') {
            $documentSizes = '0 MB';
        }

        $imageSize = $this->formatBytes(array_sum($imageSizes));
        if ($imageSizes === 'NAN') {
            $imageSizes = '0 MB';
        }

        $audioSize = $this->formatBytes(array_sum($audioSizes));
        if ($audioSizes === 'NAN') {
            $audioSizes = '0 MB';
        }

        $videoSize = $this->formatBytes(array_sum($videoSizes));
        if ($videoSizes === 'NAN') {
            $videoSizes = '0 MB';
        }

        $totalSize = $this->formatBytes(array_sum($fileSizes));
        if ($totalSize === 'NAN') {
            $totalSize = '0 MB';
        }

        $progress->setMessage("Finished...");
        $progress->finish();

        $output->writeln('');
        
        $this->message("<info>File size of documents - {$documentSize}.</info>", $output);
        $this->message("<info>File size of images - {$imageSize}.</info>", $output);
        $this->message("<info>File size of audio - {$audioSize}.</info>", $output);
        $this->message("<info>File size of video - {$videoSize}.</info>", $output);
        $this->message("<info>Total file size is - {$totalSize}.</info>", $output);

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
