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
}