<?php

namespace Eightyfour\Core\Request;

class Request
{
    private const mixed REPLACE_FLAGS = ENT_COMPAT | ENT_HTML5;
    private const string URI = 'REQUEST_URI';

    public function __construct(
        private readonly ?array $_headers = null,
        private readonly ?array $_server = null,
        private readonly ?array $_get = null,
        private readonly ?array $_post = null,
        private readonly ?array $_files = null,
        private readonly ?array $_request = null,
        private readonly ?array $_session = null,
        private readonly ?array $_env = null,
        private readonly ?array $_cookie = null,
    ) {
    }

    public static function createFromGlobals(): ?self
    {
        $headers = null;
        // @codeCoverageIgnoreStart
        if (!function_exists('getallheaders')) {
            $headers = array ();
            foreach ($_SERVER as $name => $value) {
                if (str_starts_with($name, 'HTTP_')) {
                    $headers[str_replace(
                        search: ' ',
                        replace: '-',
                        subject: ucwords(
                            string: strtolower(
                                string: str_replace(
                                    search: '_',
                                    replace: ' ',
                                    subject: substr($name, 5)
                                )
                            )
                        )
                    )] = $value;
                }
            }
        } else {
            $headers = getallheaders();
        }
        // @codeCoverageIgnoreEnd

        return new Request(
            _headers: $headers ?: null,
            _server: !empty($_SERVER) ? self::xssFilter(items: $_SERVER) : null,
            _get: !empty($_GET) ? self::xssFilter(items: $_GET) : null,
            _post: !empty($_POST) ? self::xssFilter(items: $_POST) : null,
            _files: !empty($_FILES) ? self::xssFilter(items: $_FILES) : null,
            _request: !empty($_REQUEST) ? self::xssFilter(items: $_REQUEST) : null,
            _session: !empty($_SESSION) ? self::xssFilter(items: $_SESSION) : null,
            _env: !empty($_ENV) ? self::xssFilter(items: $_ENV) : null,
            _cookie: !empty($_COOKIE) ? self::xssFilter(items: $_COOKIE) : null,
        );
    }

    public function getHeaders(): ?array
    {
        return $this->_headers;
    }

    public function getServer(): ?array
    {
        return $this->_server;
    }

    public function getGet(): ?array
    {
        return $this->_get;
    }

    public function getPost(): ?array
    {
        return $this->_post;
    }

    public function getFiles(): ?array
    {
        return $this->_files;
    }

    public function getRequest(): ?array
    {
        return $this->_request;
    }

    public function getSession(): ?array
    {
        return $this->_session;
    }

    public function getEnv(): ?array
    {
        return $this->_env;
    }

    public function getCookie(): ?array
    {
        return $this->_cookie;
    }

    public static function xssFilter(array $items): array
    {
        $results = array();
        foreach ($items as $key => $value) {
            $results[htmlspecialchars($key, self::REPLACE_FLAGS)] = is_array($value) ?
                self::xssFilter($value) : htmlspecialchars($value, self::REPLACE_FLAGS);
        }
        return $results;
    }

    public static function getRequestedUri(): ?string
    {
        return strtok(string: !empty($_SERVER[self::URI]) ? $_SERVER[self::URI] : '', token: '?')
            ?: null;
    }
}