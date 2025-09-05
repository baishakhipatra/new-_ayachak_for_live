<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="format-detection" content="date=no" />
	<meta name="format-detection" content="address=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="x-apple-disable-message-reformatting" />
    <!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=PT+Serif:400,400i,700,700i|Poppins:400,400i,700,700i" rel="stylesheet" />
    <!--<![endif]-->
	<title>Onn</title>
	<!--[if gte mso 9]>
	<style type="text/css" media="all">
		sup { font-size: 100% !important; }
	</style>
	<![endif]-->
	
	<style type="text/css" media="screen">
		/* Linked Styles */
		body { padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#ffffff; -webkit-text-size-adjust:none }
		a { color:#0000ee; text-decoration:none }
		p { padding:0 !important; margin:0 !important } 
		img { -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */ }
		.mcnPreviewText { display: none !important; }

				
		/* Mobile styles */
		@media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
			.mobile-shell { width: 100% !important; min-width: 100% !important; }
			.bg { background-size: 100% auto !important; -webkit-background-size: 100% auto !important; }
			
			<!-- .text-header, -->
			<!-- .m-center { text-align: center !important; } -->
			
			.center { margin: 0 auto !important; }
			.container { padding: 20px 10px !important }
			
			.td { width: 100% !important; min-width: 100% !important; }

			.m-br-15 { height: 15px !important; }
			.p30-15 { padding: 15px 0px !important; }
			.p0-15-30 { padding: 0px 0px 15px 0px !important; }
			.p0-15 { padding: 0px 15px !important; }
			.mpb30 { padding-bottom: 30px !important; }
			.mpb15 { padding-bottom: 15px !important; }

			.m-td,
			.m-hide { display: none !important; width: 0 !important; height: 0 !important; font-size: 0 !important; line-height: 0 !important; min-height: 0 !important; }

			.m-block { display: block !important; }

			<!-- .fluid-img img { width: 100% !important; max-width: 100% !important; height: auto !important; } -->

			.column,
			.column-dir,
			.column-top,
			.column-empty,
			.column-empty2,
			.column-dir-top { float: left !important; width: 100% !important; display: block !important; }

			.column-empty { padding-bottom: 30px !important; }
			.column-empty2 { padding-bottom: 10px !important; }

			.content-spacing { width: 15px !important; }
		}
	</style>
</head>

@php
	$coupon = \DB::table('coupons')->find($coupon_id);
@endphp

<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#f9f9f9; -webkit-text-size-adjust:none;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f9f9f9" style="margin-top:10px;">
					<tr>
						<td align="center">
							<table width="500" border="0" cellspacing="0" cellpadding="0" class="mobile-shell" style="padding: 0px 0px 0px 0px;">
								<tr>
									<td class="td" style="width:550px; min-width:550px; font-size:0pt; line-height:0pt; padding:3px; margin:0; font-weight:normal;">
										
										<!-- Header -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="padding:0px 20px 0px 20px; border-bottom: 1px solid #deedf7;">
											<tr>
												<td class="p30-15 tbrr" style="padding: 5px 0px 5px 0px;">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<th class="column-top" width="145" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td class="img m-center" style="font-size:0pt; line-height:0pt; text-align:left; padding:10px 10px 10px 10px"><a href="JavaScript:void(0);" target="_blank"><img src="https://onninternational.com/img/logo.png" style="width: 65px;"></a></td>
																	</tr>
																</table>
															</th>
															<th class="column-empty2" width="1" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;"></th>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										<!-- END Header -->
										
										<!-- Hero -->
										
										<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="padding:0px 20px 0px 20px;">
											<tr>
												<td class="p30-15" style="padding: 15px 0px 20px 0px;">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td colspan="2" class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;">Dear <span> {{$name}},<span></td>
														</tr>
														<tr>
															<td colspan="2" class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:8px;">You have earned a freshly generated coupon with <strong>{{$discountPercentage}}% discount</strong>.<br/>Find detailed information below :</td>
														</tr>
														<tr>
															<td colspan="2"><hr></td>
														</tr>
														<tr>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;">Coupon</td>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;"><strong>{{$coupon->coupon_code}}</strong></td>
														</tr>
														<tr>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;">Discount</td>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;"><strong>{{$discountPercentage}}% OFF</strong></td>
														</tr>
														<tr>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;">Validity</td>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;"><strong>{{date('j F, Y', strtotime($coupon->start_date)).' - '.date('j F, Y', strtotime($coupon->end_date))}}</strong></td>
														</tr>
														<tr>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-top:15px;">Regards </td>
														</tr>
														<tr>
															<td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-bottom:8px;">Team Onn</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>

										<!-- Footer -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px; border-top:3px solid #c10909;">
											<tr>
												<td style="width:100%; height:10px; background:#c10909;"></td>
											</tr>
											<tr>
												<td class="p0-15-30" style="padding:15px;">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="text-footer1 pb10" style="color:#ababab; font-family:Arial, sans-serif; font-size:12px; line-height:13px; text-align:left; vertical-align:middle; padding-bottom:5px;">Total Comfort &copy; 2021-{{date('Y')}}</td>

															<td class="text-footer1 pb10" style="color:#ababab; font-family:Arial, sans-serif; font-size:10px; line-height:13px; text-align:right; vertical-align:middle; padding-bottom:5px;">
																<a href="{{$settings[9]->content}}" class="margin-right:5px;"><img src="{{asset('./img/social/fb.png')}}"></a>
																<a href="{{$settings[10]->content}}" class="margin-right:5px;"><img src="{{asset('./img/social/tw.png')}}"></a>
																{{-- <a href="{{$settings[9]->content}}" class="margin-right:5px;"><img src="{{asset('./img/social/in.png')}}"></a> --}}
																<a href="{{$settings[12]->content}}" class="margin-right:5px;"><img src="{{asset('./img/social/insta.png')}}"></a>
																<a href="{{$settings[11]->content}}" class="margin-right:5px;"><img src="{{asset('./img/social/yt.png')}}"></a>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										<!-- END Footer -->
										
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>