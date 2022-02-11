<?php

declare(strict_types=1);

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Data\Protocols\Markers\Downloader\MarkerDownloaderServiceInterface;
use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Data\Protocols\Media\MediaCollectorInterface;
use App\Data\Protocols\Resources\ResourcesDownloaderInterface;
use App\Data\Protocols\User\UserUseCaseInterface;
use App\Data\UseCases\AsymCrypto\AsymmetricSigner;
use App\Data\UseCases\Authentication\Login;
use App\Data\UseCases\Authentication\SignUp;
use App\Data\UseCases\Markers\MarkerDownloader;
use App\Data\UseCases\Markers\MarkerServiceStore;
use App\Data\UseCases\Media\MediaCollectorVisitor;
use App\Data\UseCases\Resources\DeliveryMan;
use App\Data\UseCases\User\UserService;

return [
    UserUseCaseInterface::class => UserService::class,
    LoginServiceInterface::class => Login::class,
    SignUpServiceInterface::class => SignUp::class,
    MarkerDownloaderServiceInterface::class => MarkerDownloader::class,
    MarkerServiceStoreInterface::class => MarkerServiceStore::class,
    SignerInterface::class => AsymmetricSigner::class,
    MediaCollectorInterface::class => MediaCollectorVisitor::class,
    ResourcesDownloaderInterface::class => DeliveryMan::class
];
