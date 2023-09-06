<?php
namespace App\Data\UseCases\SocialLogin;

use App\Data\UseCases\SocialLogin\Errors\CantGetUserInformationException;
use App\Domain\Dto\AccountDto;
use App\Domain\Models\Enums\AuthTypes;
use League\OAuth2\Client\Provider\Google;

class GoogleAuthProvider
{
    public function __construct(private Google $provider)
    {

    }

    public function getAuthUser(string $code): AccountDto
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        // Optional: Now you have a token you can look up a users profile data
        try {
            // We got an access token, let's now get the owner details
            $ownerDetails = $this->provider->getResourceOwner($token);
            // Use these details to create a new profile
            $data = $ownerDetails->toArray();
            $email = $data['email'];
            $givenName = $data['given_name'];
            $familyName = $data['family_name'];
            $userName = strtolower($givenName) . '_' . strtolower($familyName) . base64_encode(random_bytes(18));
            $password = "";

            return new AccountDto($email, $userName, $password, AuthTypes::GOOGLE);

        } catch (\Exception $exception) {
            throw new CantGetUserInformationException();
        }
    }
}