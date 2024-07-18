<?php


namespace Shared\Criterias;

use ReflectionException;
use Shared\App\Abstract\Criteria as C;
use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Common\Type;
use Shared\App\Validator\Annotations\Common\ValidateNested;
use Shared\App\Validator\Annotations\TypeChecker\IsObject;
use Shared\App\Validator\Exceptions\ValidationErrorException;
use Shared\App\Validator\Validator;


class Criteria
{
    /**
     * @throws ReflectionException
     * @throws ValidationErrorException
     */
    static function build($criteria, $filter, $sort, $pagination): C
    {
        define('FILTER', $filter);
        define('SORT', $sort);
        define('PAGINATION', $pagination);

        $dto = new class extends C {

            #[IsOptional()]
            #[IsObject()]
            #[Type(FILTER)]
            #[ValidateNested()]
            public $filter;

            #[IsOptional()]
            #[IsObject()]
            #[Type(SORT)]
            #[ValidateNested()]
            public $sort;

            #[IsOptional()]
            #[IsObject()]
            #[Type(PAGINATION)]
            #[ValidateNested()]
            public $pagination;
        };

        return Validator::validate($criteria, $dto);
    }
}