<?php

namespace App\Interfaces;

interface ContentInterface 
{
    /** This method is to fetch terms data */
    public function termDetails();

    /** This method is to fetch privacy data */
    public function privacyDetails();

    /** This method is to fetch security data */
    public function securityDetails();

    /** This method is to fetch disclaimer data */
    public function disclaimerDetails();

    /** This method is to fetch shipping data */
    public function shippingDetails();

    /** This method is to fetch payment data */
    public function paymentDetails();

    /** This method is to fetch return data */
    public function returnDetails();

    /** This method is to fetch refund data */
    public function refundDetails();

    /** This method is to fetch service data */
    public function serviceDetails();
}