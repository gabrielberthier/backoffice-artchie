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
   * @param UuidInterface|null  $id
   * @param string    $email
   * @param string    $username
   * @param string    $password
   * @param string|null    $role
   */
  public function __construct(
    private ?UuidInterface $id = null,
    private string $email,
    private string $username,
    private string $password,
    private ?string $role = 'common'
  ) {
  }

  /**
   *
   * @return int|null
   */
  public function getId(): UuidInterface | null
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
  public function getEmail(): string
  {
    return $this->email;
  }

  /**
   * @return string
   */
  public function getPassword(): string
  {
    return $this->password;
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
      'email' => $this->email,
      'username' => $this->username,
      'password' => $this->password,
      'role' => $this->rolee
    ];
  }
}
