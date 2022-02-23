<?php
namespace JvMTECH\Tinify\Command;

use Neos\Flow\Annotations as Flow;
use JvMTECH\Tinify\Service\TinifyService;
use Neos\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class TinifyCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var TinifyService
     */
    protected $timifyService;

    /**
     * Compress image file with Timify
     *
     * @param string $file Absolute local path
     * @return void
     */
    public function imageCommand(string $file): void
    {
        $status = $this->timifyService->tinifyProcessImage($file);

        if ($status) {
            $this->outputLine('<success>✔</success> Successful');
        } else {
            $this->outputLine('<error>x</error> Failed');
        }
    }
}
