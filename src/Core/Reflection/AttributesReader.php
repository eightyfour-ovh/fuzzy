<?php

namespace Eightyfour\Core\Reflection;

use ReflectionClass;
use ReflectionException;

class AttributesReader
{

    public const string ARG = 'argument';
    public const string MTD = 'method';
    public const string DIRECTORIES = 'directories';
    public const string PATH = 'path';
    public const string NAME = 'name';
    public const string METHODS = 'methods';

    public static function readClass(string|false|null $file): ?array
    {
        if (!$file) {
            return null;
        }

        try {
            /** @var class-string $className */
            $className = self::findClass(file: $file);
            $class = new ReflectionClass(objectOrClass: $className);
            if ($class->isAbstract()) {
                return null;
            }

            $attr = [ 'className' => $class->getName() ];
            $attrName = null;
            $attributes = $class->getAttributes();
            foreach ($attributes as $attribute) {
                $attrName = $attribute->getName();
                foreach ($attribute->getArguments() as $key => $value) {
                    $attr[$attrName]['argument'][$key] = $value;
                }
            }

            $methods = $class->getMethods();
            foreach ($methods as $method) {
                $methodAttributes = $method->getAttributes();
                foreach ($methodAttributes as $attribute) {
                    foreach ($attribute->getArguments() as $key => $value) {
                        $attr[$attrName]['method'][$method->getName()]['argument'][$key] = $value;
                    }
                }
            }

            return $attr;
        } catch (ReflectionException $e) {
            throw new $e;
        }
    }

    private static function findClass(string $file): string|false
    {
        $class = false;
        $namespace = false;
        $code = file_get_contents($file) ?: '';
        $tokens = token_get_all($code);

        if (1 === \count($tokens) && \T_INLINE_HTML === $tokens[0][0]) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not contain PHP code. Did you forgot to add the "<?php" start tag at the beginning of the file?', $file));
        }

        $nsTokens = [\T_NS_SEPARATOR => true, \T_STRING => true];
        if (\defined('T_NAME_QUALIFIED')) {
            $nsTokens[\T_NAME_QUALIFIED] = true;
        }
        for ($i = 0; isset($tokens[$i]); ++$i) {
            $token = $tokens[$i];
            if (!isset($token[1])) {
                continue;
            }

            if (true === $class && \T_STRING === $token[0]) {
                return $namespace.'\\'.$token[1];
            }

            if (true === $namespace && isset($nsTokens[$token[0]])) {
                $namespace = $token[1];
                while (isset($tokens[++$i][1], $nsTokens[$tokens[$i][0]])) {
                    $namespace .= $tokens[$i][1];
                }
                $token = $tokens[$i];
            }

            if (\T_CLASS === $token[0]) {
                $skipClassToken = false;
                for ($j = $i - 1; $j > 0; --$j) {
                    if (!isset($tokens[$j][1])) {
                        if ('(' === $tokens[$j] || ',' === $tokens[$j]) {
                            $skipClassToken = true;
                        }
                        break;
                    }

                    if (\T_DOUBLE_COLON === $tokens[$j][0] || \T_NEW === $tokens[$j][0]) {
                        $skipClassToken = true;
                        break;
                    } elseif (!\in_array($tokens[$j][0], [\T_WHITESPACE, \T_DOC_COMMENT, \T_COMMENT])) {
                        break;
                    }
                }

                if (!$skipClassToken) {
                    $class = true;
                }
            }

            if (\T_NAMESPACE === $token[0]) {
                $namespace = true;
            }
        }

        return false;
    }
}