<?php

declare(strict_types=1);

namespace spaceonfire\Criteria\Expression;

use BadMethodCallException;
use Symfony\Component\PropertyAccess\PropertyPath;
use Webmozart\Expression\Constraint\Contains;
use Webmozart\Expression\Constraint\EndsWith;
use Webmozart\Expression\Constraint\Equals;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Constraint\GreaterThanEqual;
use Webmozart\Expression\Constraint\In;
use Webmozart\Expression\Constraint\IsEmpty;
use Webmozart\Expression\Constraint\IsInstanceOf;
use Webmozart\Expression\Constraint\KeyExists;
use Webmozart\Expression\Constraint\KeyNotExists;
use Webmozart\Expression\Constraint\LessThan;
use Webmozart\Expression\Constraint\LessThanEqual;
use Webmozart\Expression\Constraint\Matches;
use Webmozart\Expression\Constraint\NotEquals;
use Webmozart\Expression\Constraint\NotSame;
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Constraint\StartsWith;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\AlwaysFalse;
use Webmozart\Expression\Logic\AlwaysTrue;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Selector\All;
use Webmozart\Expression\Selector\AtLeast;
use Webmozart\Expression\Selector\AtMost;
use Webmozart\Expression\Selector\Count;
use Webmozart\Expression\Selector\Exactly;
use Webmozart\Expression\Selector\Method;

/**
 * Class ExpressionBuilder
 * @package spaceonfire\Criteria\Expression
 *
 * @method Not not(Expression $expr)
 * @method AndX andX(Expression[] $conjuncts)
 * @method OrX orX(Expression[] $disjuncts)
 * @method AlwaysTrue true()
 * @method AlwaysFalse false()
 * @method Method method(string $methodName, mixed[] $args, Expression $expr)
 * @method AtLeast atLeast(int $count, Expression $expr)
 * @method AtMost atMost(int $count, Expression $expr)
 * @method Exactly exactly(int $count, Expression $expr)
 * @method All all(Expression $expr)
 * @method Count count(Expression $expr)
 * @method Same null()
 * @method NotSame notNull()
 * @method IsEmpty isEmpty()
 * @method Not notEmpty()
 * @method IsInstanceOf isInstanceOf(string $className)
 * @method Equals equals(mixed $value)
 * @method NotEquals notEquals(mixed $value)
 * @method Same same(mixed $value)
 * @method NotSame notSame(mixed $value)
 * @method GreaterThan greaterThan(mixed $value)
 * @method GreaterThanEqual greaterThanEqual(mixed $value)
 * @method LessThan lessThan(mixed $value)
 * @method LessThanEqual lessThanEqual(mixed $value)
 * @method In in(mixed[] $values)
 * @method Matches matches(string $regExp)
 * @method StartsWith startsWith(string $prefix)
 * @method EndsWith endsWith(string $suffix)
 * @method Contains contains(string $string)
 * @method KeyExists keyExists(string $keyName)
 * @method KeyNotExists keyNotExists(string $keyName)
 */
class ExpressionFactory
{
    /**
     * Check that the value of an array key matches an expression.
     * @param string|int $key The array key.
     * @param Expression $expr The evaluated expression.
     * @return Selector The created expression.
     */
    public function key($key, Expression $expr): Selector
    {
        return Selector::makeFromKey(Expr::key($key, $expr));
    }

    /**
     * Check that the value of a object property/array key/mixed matches an expression.
     * @param string|PropertyPath $propertyName The name of the property.
     * @param Expression $expr The evaluated expression.
     * @return Selector The created expression.
     */
    public function property($propertyName, Expression $expr): Selector
    {
        return new Selector($propertyName, $expr);
    }

    /**
     * @param string $magicMethodName
     * @param mixed[] $arguments
     * @return mixed
     * @see Expr::all()
     * @see Expr::andX()
     * @see Expr::atLeast()
     * @see Expr::atMost()
     * @see Expr::contains()
     * @see Expr::count()
     * @see Expr::endsWith()
     * @see Expr::equals()
     * @see Expr::exactly()
     * @see Expr::false()
     * @see Expr::greaterThan()
     * @see Expr::greaterThanEqual()
     * @see Expr::in()
     * @see Expr::isEmpty()
     * @see Expr::isInstanceOf()
     * @see Expr::key()
     * @see Expr::keyExists()
     * @see Expr::keyNotExists()
     * @see Expr::lessThan()
     * @see Expr::lessThanEqual()
     * @see Expr::matches()
     * @see Expr::method()
     * @see Expr::not()
     * @see Expr::notEmpty()
     * @see Expr::notEquals()
     * @see Expr::notNull()
     * @see Expr::notSame()
     * @see Expr::null()
     * @see Expr::orX()
     * @see Expr::property()
     * @see Expr::same()
     * @see Expr::startsWith()
     * @see Expr::true()
     */
    public function __call(string $magicMethodName, array $arguments = [])
    {
        $proxyMethods = array_flip([
            'all',
            'andX',
            'atLeast',
            'atMost',
            'contains',
            'count',
            'endsWith',
            'equals',
            'exactly',
            'false',
            'greaterThan',
            'greaterThanEqual',
            'in',
            'isEmpty',
            'isInstanceOf',
            'key',
            'keyExists',
            'keyNotExists',
            'lessThan',
            'lessThanEqual',
            'matches',
            'method',
            'not',
            'notEmpty',
            'notEquals',
            'notNull',
            'notSame',
            'null',
            'orX',
            'property',
            'same',
            'startsWith',
            'true',
        ]);

        if (array_key_exists($magicMethodName, $proxyMethods)) {
            return call_user_func_array([Expr::class, $magicMethodName], $arguments);
        }

        throw new BadMethodCallException(
            'Call to an undefined method ' . static::class . '::' . $magicMethodName . '()'
        );
    }
}