<?php

namespace Eightyfour\Configuration;

use Eightyfour\Attribute\Config\Authentication;
use Eightyfour\Attribute\Config\Router;
use Eightyfour\Attribute\Config\Security;
use Eightyfour\Core\Core;
use Eightyfour\Core\DotEnv;
use Eightyfour\Core\Reflection\AttributesReader as Attr;
use Eightyfour\Security\Security as Sec;

class Configurator
{
    public const string DIR_PROJECT = 'project';
    public const string DIR_APP = 'app';
    public const string DIR_ROUTER = 'router';

    private array $directories = [
        self::DIR_APP => ['tests/']
    ];
    private array $security = [
        Sec::AUTH => [
            Sec::ACTIVE => true,
            Sec::PROVIDER => [
                Sec::ACTIVE => true,
            ],
        ],
    ];

    public function __construct(private readonly ?Core $core = null)
    {
    }

    public static function init(?Core $core = null): ?self
    {
        $config = new self(core: $core);

        $config->launch();

        $config
            ->setDirectory(type: self::DIR_PROJECT, directories: [$core?->getPath(path: __PROJECT__)])
            ->setDirectory(type: self::DIR_APP, directories: [$core?->getPath(path: __APP__)])
            ->setDirectory(type: self::DIR_ROUTER, directories: $config->getRouterDirectories())
            ->setSecurityToConfigure(security: $config->getSecurityConfig())
        ;

        return $config;
    }

    private function launch(): void
    {
        // TODO: start master process for configurations
    }

    public function getDirectories(string $type): ?array
    {
        return $this->directories[$type];
    }

    protected function setDirectory(string $type, array $directories, bool $merge = true): self
    {
        if (!empty($this->directories[$type])) {
            $this->directories[$type] = $merge ?
                $this->core?->merge(
                    array: $this->directories[$type],
                    extras: $directories
                ) : $directories;
        } else {
            $this->directories[$type] = $directories;
        }

        return $this;
    }

    public function getSecurity(string $section): array
    {
        return $this->security[$section];
    }

    private function getAppConfig(): ?array
    {
        return Attr::readClass(file: $this->core?->getPath(path: __APP__ . $this->core->env[DotEnv::CONFIG_KEY]));
    }

    private function getRouterDirectories(): array
    {
        $class = $this->getAppConfig();
        $routes = [];

        $directories = !empty($class[Router::class][Attr::ARG][Attr::DIRECTORIES]) ?
            $class[Router::class][Attr::ARG][Attr::DIRECTORIES] : [];
        foreach ($directories as $directory) {
            $routes[] = $this->core?->getPath(path: __APP__ . '/' . $directory);
        }

        return $routes;
    }

    private function getSecurityConfig(): array
    {
        $class = $this->getAppConfig();

        return !empty($class[Security::class][Attr::ARG]) ?
            $class[Security::class][Attr::ARG] : [];
    }

    protected function setSecurityConfig(
        string $section,
        ?string $subSection = null,
        array|bool $config = [],
        bool $merge = true
    ): self {
        if ($subSection !== null) {
            if (!empty($this->security[$section][$subSection])) {
                $this->security[$section][$subSection] = $merge ?
                    $this->core?->merge(
                        array: $this->security[$section][$subSection],
                        extras: $config
                    ) : $config;
            } else {
                $this->security[$section][$subSection] = $config;
            }
        } else {
            if (!empty($this->security[$section])) {
                $this->security[$section] = $merge ? $this->core?->merge(
                    array: $this->security[$section],
                    extras: $config
                ) : $config;
            } else {
                $this->security[$section] = $config;
            }
        }

        return $this;
    }

    private function setSecurityToConfigure(array $security): self
    {
        /** @var Authentication $auth */
        $auth = $security[Sec::AUTH];
        $provider = $auth->getProvider();
        $authenticator = $auth->getAuthenticator();

        return $this
            ->setSecurityConfig(section: Sec::ENABLED, config: $security[Sec::ENABLED], merge: false)
            ->setSecurityConfig(section: Sec::AUTH, config: [ Sec::ENABLED => $auth->isEnabled() ])
            ->setSecurityConfig(section: Sec::AUTH, subSection: Sec::PROVIDER, config: [
                Sec::ENABLED => $provider?->isEnabled(),
                Sec::PROVIDERS => $provider?->getProviders(),
                Sec::DEFAULT => $provider?->getDefault(),
                Sec::EXCEPTION_TYPE => $provider?->getExceptionType(),
            ])
            ->setSecurityConfig(section: Sec::AUTH, subSection: Sec::AUTHENTICATOR, config: [
                Sec::ENABLED => $authenticator?->isEnabled(),
                Sec::AUTHENTICATORS => $authenticator?->getAuthenticators(),
                Sec::DEFAULT => $authenticator?->getDefault(),
                Sec::FIELDS => $authenticator?->getFields(),
                Sec::EXCEPTION_TYPE => $authenticator?->getExceptionType(),
            ])
        ;
    }
}