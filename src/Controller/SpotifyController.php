<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\Spotify\SpotifyListProvider;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

final class SpotifyController extends AbstractFOSRestController
{
    /** @var SpotifyListProvider */
    private $spotifyListProvider;
    /** @var string */
    private $hatimeriaProfileId;

    public function __construct(SpotifyListProvider $spotifyListProvider, string $hatimeriaProfileId)
    {
        $this->spotifyListProvider = $spotifyListProvider;
        $this->hatimeriaProfileId  = $hatimeriaProfileId;
    }

    /**
     * @Rest\Get(path="/spotify")
     **/
    public function getSpotifyList() : Response
    {
        $spotifyList = $this->spotifyListProvider->getSpotifyList($this->hatimeriaProfileId);

        return $this->handleView($this->view($spotifyList, Response::HTTP_OK));
    }
}
