<?php

declare(strict_types=1);

namespace Flow\ETL\PHP\Type\Caster;

use Flow\ETL\Exception\CastingException;
use Flow\ETL\PHP\Type\Logical\ListType;
use Flow\ETL\PHP\Type\{Caster, Type};

final class ListCastingHandler implements CastingHandler
{
    /**
     * @param Type<array> $type
     */
    public function supports(Type $type) : bool
    {
        return $type instanceof ListType;
    }

    public function value(mixed $value, Type $type, Caster $caster, Options $options) : array
    {
        /** @var ListType $type */
        try {
            if (\is_string($value) && (\str_starts_with($value, '{') || \str_starts_with($value, '['))) {
                return \json_decode($value, true, 512, \JSON_THROW_ON_ERROR);
            }

            if (!\is_array($value)) {
                return [$caster->to($type->element())->value($value)];
            }

            $castedList = [];

            foreach ($value as $key => $item) {
                $castedList[$key] = $caster->to($type->element())->value($item);
            }

            return $castedList;
        } catch (\Throwable) {
            throw new CastingException($value, $type);
        }
    }
}
