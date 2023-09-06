<?php

declare(strict_types=1);

namespace Core\Http\Middlewares\Jwt\JwtAuthentication;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Rule to decide by request path whether the request should be authenticated or not.
 */

final class RequestPathRule implements RuleInterface
{
    /**
     * Stores all the options passed to the rule
     *
     * @var array{
     *   path: array<string>,
     *   ignore: array<string>,
     * }
     */
    private $options = [
        "path" => ["/"],
        "ignore" => []
    ];

    /**
     * @param array{
     *   path?: array<string>,
     *   ignore?: array<string>,
     * } $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function __invoke(ServerRequestInterface $request): bool
    {
        $uri = "/" . $request->getUri()->getPath();
        $uri = preg_replace("#/+#", "/", $uri);

        /* If request path is matches ignore should not authenticate. */
        foreach ((array) $this->options["ignore"] as $ignore) {
            $ignore = rtrim($ignore, "/");
            if (!!preg_match("@^{$ignore}(/.*)?$@", (string) $uri)) {
                return false;
            }
        }

        /* Otherwise check if path matches and we should authenticate. */
        foreach ((array) $this->options["path"] as $path) {
            $path = rtrim($path, "/");
            if (!!preg_match("@^{$path}(/.*)?$@", (string) $uri)) {
                return true;
            }
        }
        return false;
    }
}