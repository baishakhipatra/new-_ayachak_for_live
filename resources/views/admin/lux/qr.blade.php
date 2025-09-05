@extends('admin.layouts.app')

@section('page', 'Qrcode detail')

@section('content')
<style>
	.d-btn {
		white-space: nowrap;
		border: none;
		background: transparent;
		color: #0d6efd;
	}
</style>
<section>
 
	@php
	   	$qr1='https://onninternational.com/one';
	   	$qr2='https://onninternational.com/two';
		$qr3='https://onninternational.com/three';
		$qr4='https://onninternational.com/four';
		$qr5='https://onninternational.com/five';
		$qr6='https://onninternational.com/six';
		$qr7='https://onninternational.com/seven';
		$qr8='https://onninternational.com/eight';
		$qr9='https://onninternational.com/nine';
		$qr10='https://onninternational.com/ten';
		$qr11='https://onninternational.com/eleven';
		$qr12='https://onninternational.com/twelve';
		$qr13='https://onninternational.com/thirteen';
		$qr14='https://onninternational.com/fourteen';
		$qr15='https://onninternational.com/fifteen';
		$qr16='https://onninternational.com/sixteen';
		$qr17='https://onninternational.com/seventeen';
		$qr18='https://onninternational.com/eighteen';
		$qr19='https://onninternational.com/nineteen';
		$qr20='https://onninternational.com/twenty';
		$qr21='https://onninternational.com/twentyone';
		$qr22='https://onninternational.com/twentytwo';
		$qr23='https://onninternational.com/twentythree';
		$qr24='https://onninternational.com/twentyfour';
		$qr25='https://onninternational.com/twentyfive';
	@endphp
									  <div style="width: 120px;" class="text-center">
										
										  <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr1}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr1}}"><br><br><br>
										  <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr2}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr2}}"><br><br><br>
										  <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr3}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr3}}"><br><br><br>
									 	 <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr4}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr4}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr5}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr5}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr6}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr6}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr7}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr7}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr8}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr8}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr9}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr9}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr10}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr10}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr11}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr11}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr12}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr12}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr13}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr13}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr14}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr14}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr15}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr15}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr16}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr16}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr17}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr17}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr18}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr18}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr19}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr19}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr20}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr20}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr21}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr21}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr22}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr22}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr23}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr23}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr24}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr24}}"><br><br><br>
										   <img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr25}}&height=12&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px" id="{{$qr25}}"><br><br><br>
									 
									  {{-- <button data-href='https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$coupon}}&height=16&textsize=10&scale=6&includetext' class="d-btn" onclick="downloadFile('{{$coupon}}.png')">Download Image</button> --}}
									   

									   </div>
									  
    </div>

</section>
@endsection
@section('script')
<script src="{{ asset('backend_asset/js/printThis.js') }}"></script>
<script>
 $('#basic').on("click", function () {
      $('.print-code').printThis();
    });
	
	/*$('.save-btn').on("click", function () {
      var attr_val = '#'+$(this).attr("qr-id");
	  console.log(attr_val);
		
		let dataUrl = document.querySelector("#4AB06ADOE3").querySelector('img').src;
        downloadURI(dataUrl, attr_val+'.png');
    });
	
	function downloadURI(uri, name) {
	  var link = document.createElement("a");
	  link.download = name;
	  link.href = uri;
	  document.body.appendChild(link);
	  link.click();
	  document.body.removeChild(link);
	  delete link;
	};*/
	
	
	
	
	
	function downloadFile(fileName) {
        var div1 = document.getElementsByClassName("d-btn");
        console.log(div1);
	
        var imageurl = div1.getAttribute("data-href");
    $.ajax({
        url: imageurl,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (data) {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            a.href = url;
            a.download = fileName;
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        }
    });
    }

	
	$(document).ready(function(){
    $('.qr-txt').on("click", function(){
        value = $(this).attr('val'); //Upto this I am getting value
		console.log(value);
		toastFire("success", "Copy text successfully");
        var $temp = $("<input>");
          $("body").append($temp);
          $temp.val(value).select();
          document.execCommand("copy");
          $temp.remove();
    })
})
</script>
@endsection
