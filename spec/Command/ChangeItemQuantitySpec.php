<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Command;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use TypeError;

final class ChangeItemQuantitySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('ORDERTOKEN', 1, 5);
    }

    function it_has_order_token()
    {
        $this->orderToken()->shouldReturn('ORDERTOKEN');
    }

    function it_has_product_code()
    {
        $this->itemIdentifier()->shouldReturn(1);
    }

    function it_has_quantity()
    {
        $this->quantity()->shouldReturn(5);
    }

    function it_throws_an_exception_if_order_token_is_not_a_string()
    {
        $this->beConstructedWith(new \stdClass(), 'T_SHIRT_CODE', 1);

        $this->shouldThrow(TypeError::class)->duringInstantiation();
    }

    function it_throws_an_exception_if_quantity_is_not_less_then_1()
    {
        $this->beConstructedWith('ORDERTOKEN', 'T_SHIRT_CODE', 0);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
