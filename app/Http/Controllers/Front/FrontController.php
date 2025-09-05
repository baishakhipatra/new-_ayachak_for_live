<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductInterface;
use Illuminate\Http\Request;
use App\Models\SubscriptionMail;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\BannerTitle;
use App\Models\Event;
use App\Models\Settings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index(Request $request)
    {
        $categories = Category::where('status',1)->get();
        $featuredProducts = Product::where('is_feature',1)
            ->inRandomOrder()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $latestEvents = Event::with('eventImage')
        ->where('status',1)
        ->orderBy('start_time','desc')
        ->take(3)
        ->get();

        $banner = BannerTitle::latest()->first();

        //about us
        $about = Settings::where('page_heading', 'About Our Guru Dham')->first();

        $short_description = null;
        $page_heading = null;

        if ($about) {
            $page_heading = $about->page_heading;
            $short_description = Str::words($about->content, 50, '...');
        }


        //babamoni
        $babamoni = Settings::where('page_heading', 'LIKE', '%Sri Srimat Swami Swarupananda Paramhansa Dev%')->first();
        $babamoni_heading = $babamoni?->page_heading;
        $babamoni_sub_heading = $babamoni ? Str::words($babamoni->page_sub_heading) : null;
        $babamoni_short_description = $babamoni ? Str::words($babamoni->content, 50, '...') : null;

        //mamoni
        $mamoni = Settings::where('page_heading', 'LIKE', '%Mahasonnyasini Sri Sri Samhita Devi%')->first();
        $mamoni_heading = $mamoni?->page_heading;
        $mamoni_sub_heading = $mamoni ? Str::words($mamoni->page_sub_heading) : null;
        $mamoni_short_description = $mamoni ? Str::words($mamoni->content, 50, '...') : null;

        //mamoni
        $sadhanaDevi = Settings::where('page_heading', 'LIKE', '%Brahmacharini Sri Sri Sadhana Devi%')->first();
        $sadhanaDevi_heading = $sadhanaDevi?->page_heading;
        $sadhanaDevi_sub_heading = $sadhanaDevi ? Str::words($sadhanaDevi->page_sub_heading) : null;
        $sadhanaDevi_short_description = $sadhanaDevi ? Str::words($sadhanaDevi->content, 50, '...') : null;

        //bhaida
        $bhaida = Settings::where('page_heading', 'LIKE', '%Sri Sri Snehamoy Brahmachary%')->first();
        $bhaida_heading = $bhaida?->page_heading;
        $bhaida_sub_heading = $bhaida ? Str::words($bhaida->page_sub_heading) : null;
        $bhaida_short_description = $bhaida ? Str::words($bhaida->content, 50, '...') : null;

        //abhiksha
        $abhiksha = Settings::where('page_heading', 'LIKE', '%Abhiksha%')->first();
        $abhiksha_heading = $abhiksha?->page_heading;
        $abhiksha_short_description = $abhiksha ? Str::words($abhiksha->content, 50, '...') : null;

        //Morality Campaign
        $morality_campaign = Settings::where('page_heading', 'LIKE', '%Morality Campaign%')->first();
        $morality_campaign_heading = $morality_campaign?->page_heading;
        $morality_campaign_short_description = $morality_campaign ? Str::words($morality_campaign->content, 50, '...') : null;

        
        //ayachak ashram
        $ayachak_ashram = Settings::where('page_heading', 'LIKE', '%Ayachak Ashram%')->first();
        $ayachak_ashram_heading = $ayachak_ashram?->page_heading;
        $ayachak_ashram_short_description = $ayachak_ashram ? Str::words($ayachak_ashram->content, 50, '...') : null;

        //The Multiversity
        $the_multiversity = Settings::where('page_heading', 'LIKE', '%The Multiversity%')->first();
        $the_multiversity_heading = $the_multiversity?->page_heading;
        $the_multiversity_short_description = $the_multiversity ? Str::words($the_multiversity->content, 50, '...') : null;

        //ayurvedic medicines
        $ayurvedic_medicines = Settings::where('page_heading', 'LIKE', '%Ayurvedic Medicines%')->first();
        $ayurvedic_medicines_heading = $ayurvedic_medicines?->page_heading;
        $ayurvedic_medicines_short_description = $ayurvedic_medicines ? $ayurvedic_medicines->content : null;

        //books
        $books = Settings::where('page_heading', 'LIKE', '%Books%')->first();
        $books_heading = $books?->page_heading;
        $books_short_description = $books ? $books->content : null;

        //Voluntary Donations
        $voluntary_donations = Settings::where('page_heading', 'LIKE', '%Voluntary Donations%')->first();
        $voluntary_donations_heading = $voluntary_donations?->page_heading;
        $voluntary_donations_short_description = $voluntary_donations ? $voluntary_donations->content : null;

        //Who is Akhanda
        $akhanda = Settings::where('page_heading', 'LIKE', '%Who is Akhanda%')->first();
        $akhanda_heading = $akhanda?->page_heading;
        $akhanda_short_description = $akhanda ? Str::words($akhanda->content, 50, '...') : null;

        //What is meant by ‘OMKAR’
        $omkar = Settings::where('page_heading', 'LIKE', '%What is meant by ‘OMKAR’%')->first();
        $omkar_heading = $omkar?->page_heading;
        $omkar_short_description = $omkar ? Str::words($omkar->content, 50, '...') : null;

        //The Structure 0f Akhanda Sangathan
        $Sangathan = Settings::where('page_heading', 'LIKE', '%The Structure 0f Akhanda Sangathan%')->first();
        $Sangathan_heading = $Sangathan?->page_heading;
        $Sangathan_short_description = $Sangathan ? Str::words($Sangathan->content, 50, '...') : null;

        //WHAT IS ‘SAMABETA UPASANA?
        $samabeta_upasana = Settings::where('page_heading', 'LIKE', '%WHAT IS SAMABETA UPASANA?%')->first();
        $samabeta_upasana_heading = $samabeta_upasana?->page_heading;
        $samabeta_upasana_short_description = $samabeta_upasana ? Str::words($samabeta_upasana->content, 50, '...') : null;

        
        return view('front.index',compact('categories','featuredProducts','latestEvents','banner','page_heading',
        'short_description','babamoni_heading','babamoni_short_description','babamoni_sub_heading','mamoni_heading','mamoni_short_description','mamoni_sub_heading',
        'sadhanaDevi_heading','sadhanaDevi_short_description','sadhanaDevi_sub_heading','bhaida_heading','bhaida_short_description','bhaida_sub_heading',
        'abhiksha_heading','abhiksha_short_description','morality_campaign_heading','morality_campaign_short_description',
        'ayachak_ashram_heading','ayachak_ashram_short_description','the_multiversity_heading','the_multiversity_short_description',
        'ayurvedic_medicines_heading','ayurvedic_medicines_short_description','books_heading','books_short_description',
        'voluntary_donations_heading','voluntary_donations_short_description','akhanda_heading','akhanda_short_description',
        'omkar_heading','omkar_short_description','Sangathan_heading','Sangathan_short_description','samabeta_upasana_heading',
        'samabeta_upasana_short_description'));
    }

    public function mailSubscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {
            $mailExists = SubscriptionMail::where('email', $request->email)->first();
            if (empty($mailExists)) {
                $mail = new SubscriptionMail();
                $mail->email = $request->email;
                $mail->save();

                return response()->json(['resp' => 200, 'message' => 'Mail subscribed successfully']);
            } else {
                $mailExists->count += 1;
                $mailExists->save();

                return response()->json(['resp' => 200, 'message' => 'Thank you for showing your interest']);
            }
        } else {
            return response()->json(['resp' => 400, 'message' => $validator->errors()->first()]);
        }
    }


    public function declare(Request $request)
    {
        return view('front.declaration');
    }


    public function one(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function two(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function three(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function four(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function five(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function six(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function seven(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function eight(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function nine(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function ten(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function eleven(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function twelve(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function thirteen(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function fourteen(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }


    public function fifteen(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function sixteen(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function seventeen(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }
    public function eightteen(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function nineteen(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function twenty(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function twentyone(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }


    public function twentytwo(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function twentythree(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function twentyfour(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }

    public function twentyfive(Request $request)
    {
        return redirect('https://www.luxinnerwear.com/men/lux-cozi');
    }
    public function privacy(Request $request)
    {
        return view('front.privacyPolicy');
    }
    public function TremsAndConditions(Request $request)
    {
        return view('front.terms_conditions');
    }
    public function CustomerCare(Request $request)
    {
        return view('front.customer_care');
    }
}
