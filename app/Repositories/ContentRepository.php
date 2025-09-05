<?php

namespace App\Repositories;

use App\Interfaces\ContentInterface;
use App\Models\Settings;

class ContentRepository implements ContentInterface 
{
    /** This method is to fetch terms data */
    public function termDetails() {
        return Settings::where('page_heading', 'terms')->first();
    }

    /** This method is to fetch privacy data */
    public function privacyDetails() {
        return Settings::where('page_heading', 'privacy')->first();
    }

    /** This method is to fetch security data */
    public function securityDetails() {
        return Settings::where('page_heading', 'security')->first();
    }

    /** This method is to fetch disclaimer data */
    public function disclaimerDetails() {
        return Settings::where('page_heading', 'disclaimer')->first();
    }

    /** This method is to fetch shipping data */
    public function shippingDetails() {
        return Settings::where('page_heading', 'shipping_delivery')->first();
    }

    /** This method is to fetch payment data */
    public function paymentDetails() {
        return Settings::where('page_heading', 'payment_voucher_promotion')->first();
    }

    /** This method is to fetch return data */
    public function returnDetails() {
        return Settings::where('page_heading', 'return')->first();
    }

    /** This method is to fetch refund data */
    public function refundDetails() {
        return Settings::where('page_heading', 'cancellation_and_refund')->first();
    }

    /** This method is to fetch service data */
    public function serviceDetails() {
        return Settings::where('page_heading', 'service_contact')->first();
    }
}