<?php


namespace App\Rules\Rules;

use App\Model\v1\Customer;
use React\MySQL\QueryResult;
use Respect\Validation\Rules\AbstractRule;
use function Clue\React\Block\await;
use function React\Promise\resolve;

final class CustomerUniqueEmail extends AbstractRule
{

    public function validate($input): bool
    {

    }
}