@extends('front.layout.app')
   @section('content')
    <section class="terms_condition">
        <div class="container">
            <div class="terms_condition_content">
                <div class="terms_condition_text">
                <h4>
                    Terms and Conditions:
                </h4>
                <p class="top_p_company_info">Welcome to [Your E-commerce Website]! These terms and conditions outline the rules and regulations for the purchase and sale of products through [Your Company Name]'s website, located at [Your Website URL].</p>
                <p class="top_p">By placing an order or making a purchase through [Your E-commerce Website], you agree to be bound by these terms and conditions. If you do not agree with any part of these terms and conditions, please do not use our website.</p>
            </div>
                <div class="terms_condition_menu">
                    <ol class="terms_c_menu_ol">
                        <li class="terms_c_menu_li">
                            <h5>Order Acceptance and Pricing:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>All orders placed through [Your E-commerce Website] are subject to acceptance and availability.</li>
                                <li>Prices for products are subject to change without notice. We reserve the right to adjust prices due to market conditions, changes in product cost, or other factors.</li>
                                <li>Upon receiving your order, we will send you an email confirmation with details of the products you have ordered. This email confirmation does not signify our acceptance of your order, nor does it constitute a confirmation of our offer to sell.</li>
                                <li>We reserve the right to refuse or cancel any order for any reason, including but not limited to product availability, errors in product or pricing information, or suspicion of fraudulent activity. If your order is canceled, we will notify you and provide a full refund.</li>
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Payment:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>All payments for orders placed through [Your E-commerce Website] must be made in full at the time of purchase.</li>
                                <li>We accept payment via [list accepted payment methods], and all transactions are processed securely.</li>
                                <li>You agree to provide accurate and complete payment information when placing an order. By providing your payment information, you authorize us to charge the total order amount to your chosen payment method.</li>
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Shipping and Delivery:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>We aim to process and ship all orders within [insert timeframe] of receiving payment. However, shipping times may vary depending on product availability, shipping destination, and other factors beyond our control.</li>
                                <li>Shipping costs will be calculated and displayed at checkout. These costs are based on the shipping method selected, the weight and dimensions of the products, and the destination address.</li>
                                <li>Once your order has been shipped, you will receive a shipping confirmation email with tracking information. Please ensure that the shipping address provided is accurate and complete, as we are not responsible for orders shipped to incorrect addresses.</li>
                                <li>We are not liable for any delays, damages, or losses incurred during shipping and delivery, except where caused by our negligence or misconduct.</li>
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Returns and Exchanges:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>We want you to be satisfied with your purchase. If for any reason you are not completely satisfied, you may return the product(s) within [insert number] days of receipt for a full refund or exchange, subject to the following conditions:
                                    <ul class="terms_c_menu_ul_sub">
                                        <li>The product(s) must be unused, undamaged, and in their original packaging.</li>
                                        <li>You must contact us to request a return authorization before returning any product(s).</li>
                                        <li>Return shipping costs are the responsibility of the customer, unless the return is due to our error or the product is defective.</li>
                                    </ul>
                                </li>
                                <li>Refunds will be issued to the original payment method used for the purchase, within [insert timeframe] of receiving the returned product(s).
                                </li>
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Intellectual Property:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>All content and materials available on [Your E-commerce Website], including but not limited to text, graphics, logos, images, product descriptions, and designs, are the intellectual property of [Your Company Name] or its licensors and are protected by copyright, trademark, and other intellectual property laws.</li>
                                <li>You may not use, reproduce, distribute, or modify any content from [Your E-commerce Website] without our prior written consent.</li>
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Limitation of Liability:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>In no event shall [Your Company Name], its directors, officers, employees, or affiliates be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or in connection with your use of [Your E-commerce Website] or the purchase and sale of products through the website.</li>
                                <li>Our total liability for any claim arising out of or relating to these terms and conditions or the transactions contemplated herein shall not exceed the total amount paid by you for the products giving rise to such claim.</li>
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Governing Law and Dispute Resolution:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>These terms and conditions shall be governed by and construed in accordance with the laws of [insert governing law jurisdiction].</li>
                                <li>Any dispute, controversy, or claim arising out of or relating to these terms and conditions or the breach, termination, or invalidity thereof shall be resolved through arbitration in accordance with the rules of [insert arbitration institution], by [insert number] arbitrators appointed in accordance with said rules. The place of arbitration shall be [insert arbitration venue], and the language of the arbitration shall be [insert arbitration language].</li>
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Changes to Terms and Conditions:</h5>
                            <ul class="terms_c_menu_ul">
                                <li>We reserve the right to update, modify, or replace these terms and conditions at any time without prior notice. Any changes will be effective immediately upon posting on [Your E-commerce Website]. It is your responsibility to review these terms and conditions periodically for changes.</li>
                                
                            </ul>
                        </li>
                        <li class="terms_c_menu_li">
                            <h5>Product Content Disclaimer:</h5>
                            <p class="p_disclaimer">The information provided about our products is for general informational purposes only. We make no representations or warranties of any kind, express or implied, regarding the accuracy, completeness, reliability, suitability, or availability of the information. Any reliance you place on such information is strictly at your own risk. Always read labels, warnings, and directions provided with the product before use.</p>
                        </li>
                    </ol>
                    <p class="bottom_p">
                        If you have any questions or concerns about these terms and conditions, please contact us at [insert contact email or phone number].
                    </p>
                    <p class="p_thanks">Thank you for shopping with us!</p>
                </div>
            </div>
        </div>
    </section>
 
    @endsection
   
   @section('script')

   @endsection