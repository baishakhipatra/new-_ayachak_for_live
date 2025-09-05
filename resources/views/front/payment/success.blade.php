@extends('front.layout.app')
@section('content')
<style>
    /* payment success and fail page start */

    .payment_sucess{
    padding: 80px 0px;
    }

    .success_text{
    margin: 0;
    text-align: center;
    }
    .success_text h2 {
    color: #000;
    font-size: 35px;
    font-weight: 500;
    margin: 10px 0px;
    }
    .success_text h5 {
    color: #000;
    font-size: 22px;
    font-weight: 500;
    margin-bottom: 10px;
    }
    .success_text p {
    color: #000;
    font-size: 16px;
    margin-bottom: 20px;
    }

    .continue_shopping{
    background-color: #141b4b;
    color: #fff;
    font-weight: 500;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 30px;
    margin: 5px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    }
    .continue_shopping:hover{
    background-color: #239f48;
    color: #fff;
    }


    /* payment success and fail page end */
</style>
<section class="payment_sucess">
    <div class="container">
        <div class="success_text">
            <img src="{{asset('images/success.png')}}" alt="Success" class="success">
            <h2>Payment Successfull!</h2>
            <h5>Your request has been processed successfully</h5>
            <p>Thank you for your purchase.</p>
            <div class="shopping_btn">
                <button class="continue_shopping">Continue Shopping</button>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        setTimeout(() => {
            window.location = '{{url("/")}}';
        }, 5000);
    </script>
@endsection