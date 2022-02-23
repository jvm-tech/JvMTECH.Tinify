<?php
namespace JvMTECH\Tinify\Aspects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use JvMTECH\Tinify\Service\TinifyService;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\Tag;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class ThumbnailAspect
{
    /**
     * @var array
     * @Flow\InjectConfiguration
     */
    protected $settings;

    /**
     * @Flow\Inject
     * @var TinifyService
     */
    protected $tinifyService;

    /**
     * @Flow\AfterReturning("method(Neos\Media\Domain\Model\Thumbnail->refresh())")
     * @param \Neos\Flow\Aop\JoinPointInterface $joinPoint The current join point
     * @return void
     */
    public function tinifyThumbnail(JoinPointInterface $joinPoint)
    {
        /** @var \Neos\Media\Domain\Model\Thumbnail $thumbnail */
        $thumbnail = $joinPoint->getProxy();
        $thumbnailResource = $thumbnail->getResource();
        if (!$thumbnailResource) {
            return;
        }

        $streamMetaData = stream_get_meta_data($thumbnailResource->getStream());
        $pathAndFilename = $streamMetaData['uri'];

        $file = escapeshellarg($pathAndFilename);
        $imageType = $thumbnailResource->getMediaType();

        if (array_key_exists($imageType, $this->settings['formats']) && $this->settings['formats'][$imageType]['enabled'] === true) {
            $this->tinifyService->tinifyQueueImage($imageType, $file);
        }
    }
}
