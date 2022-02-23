<?php
namespace JvMTECH\Tinify\Service;

use Neos\Flow\Annotations as Flow;
use Flowpack\JobQueue\Common\Annotations as Job;
use Psr\Log\LoggerInterface;

/**
 * @Flow\Scope("singleton")
 */
class TinifyService
{
    /**
     * @var array
     * @Flow\InjectConfiguration()
     */
    protected $settings;

    /**
     * @Flow\Inject
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @Job\Defer(queueName="tinify")
     */
    public function tinifyQueueImage($imageType, $file) {
        $this->tinifyImage($imageType, $file);
    }

    public function tinifyImage($imageType, $file) {
        $this->logger->info('Tinify Job: ' . $imageType . ' ' . $file);

        if ($imageType === 'image/jpeg' || $imageType === 'image/png') {
            $command = 'FLOW_CONTEXT=' . $_ENV['FLOW_CONTEXT'] . ' ' . FLOW_PATH_ROOT . 'flow tinify:image --file ' . $file;

            $output = [];
            exec($command, $output, $result);
            $failed = (int)$result !== 0;

            return !$failed;
        }

        return false;
    }

    public function tinifyProcessImage($file) {
        try {
            $filesizeBefore = filesize($file);
            \Tinify\setKey($this->settings['apiKey']);
            \Tinify\fromFile($file)->toFile($file);
            $filesizeAfter = filesize($file);
            $reduction = 100 - ceil(100 / $filesizeBefore * $filesizeAfter);
        } catch (\Exception $e) {
            $this->logger->error('Tinify process image failed', ['error' => $e->getMessage()]);
            return false;
        }

        $this->logger->info('Tinify processed image ' . $file . ' reduced by ' . $reduction . '%');
        return true;
    }
}
