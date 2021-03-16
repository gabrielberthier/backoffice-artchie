<?php
namespace App\Data\Protocols\User;

use App\Domain\Repositories\UserRepository;



Interface UserUseCaseInterface extends UserSelectionUseCaseInterface{
    public function __construct(UserRepository $userRepository);
}