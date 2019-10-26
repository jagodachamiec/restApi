<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\ComposerJsonReader\ComposerJsonReader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

final class VersionController extends AbstractFOSRestController
{
    /** @var ComposerJsonReader */
    private $composerJsonReader;

    public function __construct(ComposerJsonReader $composerJsonReader)
    {
        $this->composerJsonReader = $composerJsonReader;
    }

    /**
     * @Rest\Get(path="/")
     **/
    public function getVersion() : Response
    {
        $version = $this->composerJsonReader->getVersion();

        return $this->handleView($this->view(['version' => $version], Response::HTTP_OK));
    }
}
