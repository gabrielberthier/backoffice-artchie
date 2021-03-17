<?php

declare(strict_types=1);

namespace App\Domain\Models;

use JsonSerializable;
use \Ramsey\Uuid\UuidInterface;

/**
 * @Entity
 * @Table(name="accounts")
 **/
class Account implements JsonSerializable
{

  /**
   * @param UuidInterface  $id
   * @param string    $username
   * @param string    $firstName
   * @param string    $lastName
   */
  public function __construct(
    private UuidInterface $id,
    private string $email,
    private string $username,
    private string $password,
    private ?string $role
  ) {
  }

  /**
   * @return int|null
   */
  public function getId(): UuidInterface
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getUsername(): string
  {
    return $this->username;
  }

  /**
   * @return string
   */
  public function getFirstName(): string
  {
    return $this->firstName;
  }

  /**
   * @return string
   */
  public function getLastName(): string
  {
    return $this->lastName;
  }

  /**
   * @return string|null
   */
  public function getRole(): ?string
  {
    return $this->role;
  }

  /**
   * @return array
   */
  public function jsonSerialize()
  {
    return [
      'id' => $this->id,
      'username' => $this->username,
      'firstName' => $this->firstName,
      'lastName' => $this->lastName,
      'role' => $this->role
    ];
  }
}
