<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Checkout;

use Sylius\Bundle\FlowBundle\Process\Builder\ProcessBuilderInterface;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Sylius\Component\Cart\Context\CartContextInterface;
use Sylius\Component\Core\Model\OrderInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class CheckoutProcessScenario implements ProcessScenarioInterface
{
    /**
     * @var CartContextInterface
     */
    protected $cartContext;

    /**
     * @param CartContextInterface $cartContext
     */
    public function __construct(CartContextInterface $cartContext)
    {
        $this->cartContext = $cartContext;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ProcessBuilderInterface $builder)
    {
        $builder
            ->add('security', 'sylius_checkout_security')
            ->add('addressing', 'sylius_checkout_addressing')
            ->add('shipping', 'sylius_checkout_shipping')
            ->add('payment', 'sylius_checkout_payment')
            ->add('finalize', 'sylius_checkout_finalize')
            ->setDisplayRoute('sylius_checkout_display')
            ->setForwardRoute('sylius_checkout_forward')
            ->setRedirect('sylius_shop_checkout_thank_you')
            ->validate(function () {
                return !$this->getCurrentCart()->isEmpty();
            })
        ;
    }

    /**
     * @return OrderInterface
     */
    protected function getCurrentCart()
    {
        return $this->cartContext->getCart();
    }
}
