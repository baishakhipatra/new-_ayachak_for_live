<footer>
    <div class="footer-top">
        <div class="container">
            <div class="news-place">
                <div class="left-text">
                    <h2>Enjoy a Special 50% New Client Discount and Boost Your Health!</h2>
                </div>
                <div class="news-input-place">
                    <form id="" action="">
                        <input type="email" id="" placeholder="Enter your email address">
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
            <div class="footer-menu-place">
                <div class="row">
                    <div class="col-md-4 col-lg-2">
                        <a href="index.html" class="footer-logo">
                            <img src="{{asset('assets/images/logo.png')}}" alt="">
                        </a>

                        <ul class="social-list">
                            <li>
                                <a href="https://www.facebook.com/ayachakashrama/" target="_blank">
                                    <i class="fa-brands fa-square-facebook"></i>
                                    <span>Facebook</span>
                                </a>
                            </li>
                           <li>
                                <a href="https://www.youtube.com/channel/UC3MwGwjIiybZEfcYFogMSAg" target="_blank">
                                    <i class="fa-brands fa-youtube"></i>
                                    <span>Youtube</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <h3>Useful Links</h3>
                        <ul class="footer-list">
                            <li>
                                <a href="{{route('front.home')}}">Home</a>
                            </li>
                            <li>
                                <a href="{{route('front.about-us.index')}}">About Us</a>
                            </li>
                            <li>
                                <a href="{{ route('front.shop.list', ['category' => 'Book']) }}">Books</a>
                            </li>
                            <li>
                                <a href="{{ route('front.shop.list', ['category' => 'Medicine']) }}">Medicines</a>
                            </li>
                            <li>
                                <a href="{{route('front.event.index')}}">Events</a>
                            </li>
                            <li>
                                <a href="">Centre</a>
                            </li>
                            <li>
                                <a href="">Diksha</a>
                            </li>
                            <li>
                                <a href="">Conclusion</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <h3>Legal</h3>
                        <ul class="footer-list">
                            <li>
                                <a href="">Privacy Statement</a>
                            </li>
                            <li>
                                <a href="">Terms and conditions</a>
                            </li>
                            <li>
                                <a href="">Refund and Cancellation Policy</a>
                            </li>
                            <li>
                                <a href="">Disclaimer</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <address>
                            <h3>Head office</h3>
                            <p>GURU-DHAM <br>P-238, Swami Swarupananda Sarani <br>P.O. - Kankurgachi, <br>Kolkata-700054 <br> Phone-2320-8455/5559</p>
                        </address>
                        <address>
                            <h3>Ayachak Ashrama, Varanasi</h3>
                            <p>D46/19B, Swarupananda Street, Luxa (Behind Luxa Thana), Varanasi, Uttar Pradesh, <br> Pin-221010, <br>Phone-(0542)2452376</p>
                        </address>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-overlay"></div>
    </div>
    <div class="copyright-place">
        <div class="container">
            © The information to be given is as per discretion of "Ayachak Ashrama" and "The Multiversity".
        </div>
    </div>
</footer>

<!--banner modal-->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
        <div class="video-holder">
            <div class="off-modal" data-bs-dismiss="modal" aria-label="Close">
                <img src="{{asset('assets/images/cross.svg')}}">
            </div>
            <video  controls id="modalVideo">
                <source src="{{asset('assets/images/intro_video.mp4')}}" type="video/mp4">
            </video>
        </div>
    </div>
  </div>
</div>
<div class="overlay"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
