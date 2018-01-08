<?php

namespace Fooman\ConnectAddonCanada\Observer;

class LookupTaxRate implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Fooman\Connect\Model\System\TaxOptions
     */
    protected $connectSystemTaxOptions;

    protected $taxRates;

    public function __construct(
        \Fooman\Connect\Model\System\TaxOptions $connectSystemTaxOptions
    ) {
        $this->connectSystemTaxOptions = $connectSystemTaxOptions;
    }


    /**
     * We currently can't pass into Xero multiple tax rates
     * Look up the cases we can handle for Canada here
     * requires a tax rate set up in Xero that is the combination
     * of the multiple ones in Magento
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $item = $observer->getEvent()->getOrderItem();

        //Quebec
        if ($item->getTaxPercent() == '14.975') {
            $transport = $observer->getEvent()->getTransport();
            if (null === $this->taxRates) {
                $this->taxRates = $this->connectSystemTaxOptions->toOptionArraySafe('options-only');
            }

            if (isset($this->taxRates['14.9750'])) {
                $transport->setTaxRate($this->taxRates['14.9750']);
            }
        }
    }

}
