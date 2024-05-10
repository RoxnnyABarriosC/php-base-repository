<?php


namespace Shared\Criterias;

use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as v;
use Shared\App\Abstract\Criteria as C;


class Criteria
{
    static function build($filter, $sort, $pagination): C
    {
        define('FILTER', $filter);
        define('SORT', $sort);
        define('PAGINATION', $pagination);

        return new class extends C {
            public $filter;
            public $sort;
            public $pagination;

            public static function schema(): v|ChainedValidator
            {
                $f = new (FILTER)();
                $s = new (SORT)();
                $p = new (PAGINATION)();

//                return v::attribute('filter', $f::schema());
//                    ->attribute('sort', v::optional($s::schema()))
//                    ->attribute('pagination', v::optional($p::schema()));

                return
                    v::attribute('filter', $f::schema());
//                        ->attribute('sort', $s::schema())
//                        ->attribute('pagination', $p::schema())
            }

            public function nestedProperties(): array
            {
                return [
                    'filter' => FILTER,
                    'sort' => SORT,
                    'pagination' => PAGINATION
                ];
            }
        };
    }
}