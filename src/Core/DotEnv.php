<?php

namespace Eightyfour\Core;

use Eightyfour\Exception\Exception;
use Eightyfour\Trait\UtilsTrait;

final class DotEnv
{
    use UtilsTrait;

    public const VARNAME_REGEX = '(?i:_?[A-Z][A-Z0-9_]*+)';
    public const STATE_VARNAME = 0;
    public const STATE_VALUE = 1;
    public const string CONFIG_KEY = 'APP_CONF';

    private string $path;
    private int $cursor;
    private int $lineno;
    private string $data;
    private int $end;
    private array $values = [];
    private string $envKey;
    private string $debugKey;
    private array $prodEnvs = ['prod'];
    private bool $usePutenv = false;

    public function __construct(string $envKey = 'APP_ENV', string $debugKey = 'APP_DEBUG')
    {
        $this->envKey = $envKey;
        $this->debugKey = $debugKey;
    }

    public static function init(): void
    {
        $self = new self();
        $self->load(path: __PROJECT__ . '.env');
    }

    public function setProdEnvs(array $prodEnvs): self
    {
        $this->prodEnvs = $prodEnvs;

        return $this;
    }

    public function usePutenv(bool $usePutenv = true): self
    {
        $this->usePutenv = $usePutenv;

        return $this;
    }

    public function load(string $path, string ...$extraPaths): void
    {
        $this->doLoad(false, \func_get_args());
    }

    public function loadEnv(string $path, ?string $envKey = null, string $defaultEnv = 'dev', array $testEnvs = ['test'], bool $overrideExistingVars = false): void
    {
        $k = $envKey ?? $this->envKey;

        if (is_file($path) || !is_file($p = "$path.dist")) {
            $this->doLoad($overrideExistingVars, [$path]);
        } else {
            $this->doLoad($overrideExistingVars, [$p]);
        }

        if (null === $env = $_SERVER[$k] ?? $_ENV[$k] ?? null) {
            $this->populate([$k => $env = $defaultEnv], $overrideExistingVars);
        }

        if (!\in_array($env, $testEnvs, true) && is_file($p = "$path.local")) {
            $this->doLoad($overrideExistingVars, [$p]);
            $env = $_SERVER[$k] ?? $_ENV[$k] ?? $env;
        }

        if ('local' === $env) {
            return;
        }

        if (is_file($p = "$path.$env")) {
            $this->doLoad($overrideExistingVars, [$p]);
        }

        if (is_file($p = "$path.$env.local")) {
            $this->doLoad($overrideExistingVars, [$p]);
        }
    }

    public function bootEnv(string $path, string $defaultEnv = 'dev', array $testEnvs = ['test'], bool $overrideExistingVars = false): void
    {
        $p = $path.'.local.php';
        $env = is_file($p) ? include $p : null;
        $k = $this->envKey;

        if (\is_array($env) && ($overrideExistingVars || !isset($env[$k]) || ($_SERVER[$k] ?? $_ENV[$k] ?? $env[$k]) === $env[$k])) {
            $this->populate($env, $overrideExistingVars);
        } else {
            $this->loadEnv($path, $k, $defaultEnv, $testEnvs, $overrideExistingVars);
        }

        $_SERVER += $_ENV;

        $k = $this->debugKey;
        $debug = $_SERVER[$k] ?? !\in_array($_SERVER[$this->envKey], $this->prodEnvs, true);
        $_SERVER[$k] = $_ENV[$k] = (int) $debug || (!\is_bool($debug) && filter_var($debug, \FILTER_VALIDATE_BOOL)) ? '1' : '0';
    }

    public function overload(string $path, string ...$extraPaths): void
    {
        $this->doLoad(true, \func_get_args());
    }

    public function populate(array $values, bool $overrideExistingVars = false): void
    {
        $updateLoadedVars = false;
        $loadedVars = array_flip(explode(',', $_SERVER['DOTENV_VARS'] ?? $_ENV['DOTENV_VARS'] ?? ''));

        foreach ($values as $name => $value) {
            $notHttpName = !str_starts_with($name, 'HTTP_');
            if (isset($_SERVER[$name]) && $notHttpName && !isset($_ENV[$name])) {
                $_ENV[$name] = $_SERVER[$name];
            }

            // don't check existence with getenv() because of thread safety issues
            if (!isset($loadedVars[$name]) && !$overrideExistingVars && isset($_ENV[$name])) {
                continue;
            }

            if ($this->usePutenv) {
                putenv("$name=$value");
            }

            $_ENV[$name] = $value;
            if ($notHttpName) {
                $_SERVER[$name] = $value;
            }

            if (!isset($loadedVars[$name])) {
                $loadedVars[$name] = $updateLoadedVars = true;
            }
        }

        if ($updateLoadedVars) {
            unset($loadedVars['']);
            $loadedVars = implode(',', array_keys($loadedVars));
            $_ENV['DOTENV_VARS'] = $_SERVER['DOTENV_VARS'] = $loadedVars;

            if ($this->usePutenv) {
                putenv('DOTENV_VARS='.$loadedVars);
            }
        }
    }

    public function parse(string|false $data, string $path = '.env'): array
    {
        $this->path = $path;
        $this->data = str_replace(["\r\n", "\r"], "\n", $data ?: '');
        $this->lineno = 1;
        $this->cursor = 0;
        $this->end = \strlen($this->data);
        $state = self::STATE_VARNAME;
        $this->values = [];
        $name = '';

        $this->skipEmptyLines();

        while ($this->cursor < $this->end) {
            switch ($state) {
                case self::STATE_VARNAME:
                    $name = $this->lexVarname();
                    $state = self::STATE_VALUE;
                    break;

                case self::STATE_VALUE:
                    $this->values[$name] = $this->lexValue();
                    $state = self::STATE_VARNAME;
                    break;
            }
        }

        if (self::STATE_VALUE === $state) {
            $this->values[$name] = '';
        }

        try {
            return $this->values;
        } finally {
            $this->values = [];
            unset($this->path, $this->cursor, $this->lineno, $this->data, $this->end);
        }
    }

    private function lexVarname(): string
    {
        // var name + optional export
        if (!preg_match('/(export[ \t]++)?('.self::VARNAME_REGEX.')/A', $this->data, $matches, 0, $this->cursor)) {
            throw $this->createFormatException('Invalid character in variable name');
        }
        $this->moveCursor($matches[0]);

        if ($this->cursor === $this->end || "\n" === $this->data[$this->cursor] || '#' === $this->data[$this->cursor]) {
            if ($matches[1]) {
                throw $this->createFormatException('Unable to unset an environment variable');
            }

            throw $this->createFormatException('Missing = in the environment variable declaration');
        }

        if (' ' === $this->data[$this->cursor] || "\t" === $this->data[$this->cursor]) {
            throw $this->createFormatException('Whitespace characters are not supported after the variable name');
        }

        if ('=' !== $this->data[$this->cursor]) {
            throw $this->createFormatException('Missing = in the environment variable declaration');
        }
        ++$this->cursor;

        return $matches[2];
    }

    private function lexValue(): string
    {
        if (preg_match('/[ \t]*+(?:#.*)?$/Am', $this->data, $matches, 0, $this->cursor)) {
            $this->moveCursor($matches[0]);
            $this->skipEmptyLines();

            return '';
        }

        if (' ' === $this->data[$this->cursor] || "\t" === $this->data[$this->cursor]) {
            throw $this->createFormatException('Whitespace are not supported before the value');
        }

        $loadedVars = array_flip(explode(',', $_SERVER['DOTENV_VARS'] ?? $_ENV['DOTENV_VARS'] ?? ''));
        unset($loadedVars['']);
        $v = '';

        do {
            if ("'" === $this->data[$this->cursor]) {
                $len = 0;

                do {
                    if ($this->cursor + ++$len === $this->end) {
                        $this->cursor += $len;

                        throw $this->createFormatException('Missing quote to end the value');
                    }
                } while ("'" !== $this->data[$this->cursor + $len]);

                $v .= substr($this->data, 1 + $this->cursor, $len - 1);
                $this->cursor += 1 + $len;
            } elseif ('"' === $this->data[$this->cursor]) {
                $value = '';

                if (++$this->cursor === $this->end) {
                    throw $this->createFormatException('Missing quote to end the value');
                }

                while ('"' !== $this->data[$this->cursor] || ('\\' === $this->data[$this->cursor - 1] && '\\' !== $this->data[$this->cursor - 2])) {
                    $value .= $this->data[$this->cursor];
                    ++$this->cursor;

                    if ($this->cursor === $this->end) {
                        throw $this->createFormatException('Missing quote to end the value');
                    }
                }
                ++$this->cursor;
                $value = str_replace(['\\"', '\r', '\n'], ['"', "\r", "\n"], $value);
                $resolvedValue = $value;
                $resolvedValue = $this->resolveVariables($resolvedValue, $loadedVars) ?: '';
                $resolvedValue = str_replace('\\\\', '\\', $resolvedValue);
                $v .= $resolvedValue;
            } else {
                $value = '';
                $prevChr = $this->data[$this->cursor - 1];
                while ($this->cursor < $this->end && !\in_array($this->data[$this->cursor], ["\n", '"', "'"], true) && !((' ' === $prevChr || "\t" === $prevChr) && '#' === $this->data[$this->cursor])) {
                    if ('\\' === $this->data[$this->cursor] && isset($this->data[$this->cursor + 1]) && ('"' === $this->data[$this->cursor + 1] || "'" === $this->data[$this->cursor + 1])) {
                        ++$this->cursor;
                    }

                    $value .= $prevChr = $this->data[$this->cursor];

                    if ('$' === $this->data[$this->cursor] && isset($this->data[$this->cursor + 1]) && '(' === $this->data[$this->cursor + 1]) {
                        ++$this->cursor;
                        $value .= '('.$this->lexNestedExpression().')';
                    }

                    ++$this->cursor;
                }
                $value = rtrim($value);
                $resolvedValue = $value;
                $resolvedValue = $this->resolveVariables($resolvedValue, $loadedVars) ?: '';
                $resolvedValue = str_replace('\\\\', '\\', $resolvedValue);

                if ($resolvedValue === $value && preg_match('/\s+/', $value)) {
                    throw $this->createFormatException('A value containing spaces must be surrounded by quotes');
                }

                $v .= $resolvedValue;

                if ($this->cursor < $this->end && '#' === $this->data[$this->cursor]) {
                    break;
                }
            }
        } while ($this->cursor < $this->end && "\n" !== $this->data[$this->cursor]);

        $this->skipEmptyLines();

        return $v;
    }

    private function lexNestedExpression(): string
    {
        ++$this->cursor;
        $value = '';

        while ("\n" !== $this->data[$this->cursor] && ')' !== $this->data[$this->cursor]) {
            $value .= $this->data[$this->cursor];

            if ('(' === $this->data[$this->cursor]) {
                $value .= $this->lexNestedExpression().')';
            }

            ++$this->cursor;

            if ($this->cursor === $this->end) {
                throw $this->createFormatException('Missing closing parenthesis.');
            }
        }

        if ("\n" === $this->data[$this->cursor]) {
            throw $this->createFormatException('Missing closing parenthesis.');
        }

        return $value;
    }

    private function skipEmptyLines(): void
    {
        if (preg_match('/(?:\s*+(?:#[^\n]*+)?+)++/A', $this->data, $match, 0, $this->cursor)) {
            $this->moveCursor($match[0]);
        }
    }

    private function resolveVariables(string $value, array $loadedVars): ?string
    {
        if (!str_contains($value, '$')) {
            return $value;
        }

        $regex = '/
            (?<!\\\\)
            (?P<backslashes>\\\\*)             # escaped with a backslash?
            \$
            (?!\()                             # no opening parenthesis
            (?P<opening_brace>\{)?             # optional brace
            (?P<name>'.self::VARNAME_REGEX.')? # var name
            (?P<default_value>:[-=][^\}]++)?   # optional default value
            (?P<closing_brace>\})?             # optional closing brace
        /x';

        $value = preg_replace_callback($regex, function ($matches) use ($loadedVars) {
            // odd number of backslashes means the $ character is escaped
            if (1 === \strlen($matches['backslashes']) % 2) {
                return substr($matches[0], 1);
            }

            // unescaped $ not followed by variable name
            if (!isset($matches['name'])) {
                return $matches[0];
            }

            if ('{' === $matches['opening_brace'] && !isset($matches['closing_brace'])) {
                throw $this->createFormatException('Unclosed braces on variable expansion');
            }

            $name = $matches['name'];
            if (isset($loadedVars[$name]) && isset($this->values[$name])) {
                $value = $this->values[$name];
            } elseif (isset($_ENV[$name])) {
                $value = $_ENV[$name];
            } elseif (isset($_SERVER[$name]) && !str_starts_with($name, 'HTTP_')) {
                $value = $_SERVER[$name];
            } elseif (isset($this->values[$name])) {
                $value = $this->values[$name];
            } else {
                $value = (string) getenv($name);
            }

            if ('' === $value && isset($matches['default_value']) && '' !== $matches['default_value']) {
                $unsupportedChars = strpbrk($matches['default_value'], '\'"{$');
                if (false !== $unsupportedChars) {
                    throw $this->createFormatException(sprintf('Unsupported character "%s" found in the default value of variable "$%s".', $unsupportedChars[0], $name));
                }

                $value = substr($matches['default_value'], 2);

                if ('=' === $matches['default_value'][1]) {
                    $this->values[$name] = $value;
                }
            }

            if (!$matches['opening_brace'] && isset($matches['closing_brace'])) {
                $value .= '}';
            }

            return $matches['backslashes'].$value;
        }, $value);

        return $value;
    }

    private function moveCursor(string $text): void
    {
        $this->cursor += \strlen($text);
        $this->lineno += substr_count($text, "\n");
    }

    private function createFormatException(string $message): Exception
    {
        return new Exception($message);
    }

    private function doLoad(bool $overrideExistingVars, array $paths): void
    {
        foreach ($paths as $path) {
            if (!is_readable($path) || is_dir($path)) {
                throw new Exception(message: "$path does not exists.");
            }

            $this->populate($this->parse(file_get_contents($path), $path), $overrideExistingVars);
        }
    }

    public static function isProd(): bool
    {
        return $_ENV[(new self())->envKey] === Kernel::ENV_PROD;
    }

    public static function isTest(): bool
    {
        return $_ENV[(new self())->envKey] === Kernel::ENV_TEST;
    }

    public static function getOnlyFrameworkVars(): array
    {
        $variables = [];
        if (!empty($_ENV['DOTENV_VARS'])) {
            $vars = explode(separator: ',', string: $_ENV['DOTENV_VARS']);
            foreach ($vars as $var) {
                $variables[$var] = $_ENV[$var];
            }
        }

        return $variables;
    }
}