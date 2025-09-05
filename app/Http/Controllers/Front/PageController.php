<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function AboutUs(){
        $page_heading = 'About Our Guru Dham';

        $setting = Settings::where('page_heading', $page_heading)->first();

        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.about-us', compact('page_heading', 'description'));
    }

    public function babamoni(){
        $page_heading = 'Sri Srimat Swami Swarupananda Paramhansa Dev';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        $sub_heading = $setting->page_sub_heading;

        return view('front.pages.babamoni', compact('page_heading', 'description','sub_heading'));
    }

    public function mamoni(){
        $page_heading = 'Mahasonnyasini Sri Sri Samhita Devi';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        $sub_heading = $setting->page_sub_heading;

        return view('front.pages.mamoni', compact('page_heading', 'description','sub_heading'));
    }

    public function sadhanaDevi(){
        $page_heading = 'Brahmacharini Sri Sri Sadhana Devi';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        $sub_heading = $setting->page_sub_heading;

        return view('front.pages.sadhana-devi', compact('page_heading', 'description','sub_heading'));
        
    }


    public function bhaida(){
        $page_heading = 'Sri Sri Snehamoy Brahmachary';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        $sub_heading = $setting->page_sub_heading;

        return view('front.pages.bhaida', compact('page_heading', 'description','sub_heading'));
        
    }

    public function abhiksha(){
        $page_heading = 'Abhiksha';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.abhiksha', compact('page_heading', 'description'));
    }

    public function moralityCompaign(){
        $page_heading = 'Morality Campaign';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.abhiksha', compact('page_heading', 'description'));
    }

    public function ayachakAshram()
    {
        $page_heading = 'Ayachak Ashram';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.ayachak-ashram', compact('page_heading', 'description'));
    }

    
    public function theMultiversity()
    {
        $page_heading = 'The Multiversity';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.ayachak-ashram', compact('page_heading', 'description'));
    }

    public function akhanda()
    {
        $page_heading = 'Who is Akhanda';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.akhanda', compact('page_heading', 'description'));
    }

    public function omkar(){
        $page_heading = 'What is meant by ‘OMKAR’';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.omkar', compact('page_heading', 'description')); 
    }

    public function sangathan(){
        $page_heading = 'The Structure 0f Akhanda Sangathan';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.sangathan', compact('page_heading', 'description')); 
    }

    
    public function samabeta_upasana(){
        $page_heading = 'WHAT IS SAMABETA UPASANA?';

       $setting = Settings::where('page_heading', 'like', "%{$page_heading}%")->first();
        //dd($setting);
        if (!$setting) {
            abort(404, 'Page settings not found.');
        }

        $description = $setting->content;

        return view('front.pages.samabeta-upasana', compact('page_heading', 'description')); 
    }



}
