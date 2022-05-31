// Mapper script for web SIP call initiate


$( document ).ready(function() {

	myext = $("div.webcall-ext-source").html();

    var hexid = (Math.round(0xFFFFFF * Math.random()).toString(16) ).replace(/([a-f0-9]{6}).+/, "#$1").toUpperCase();
    $("<iframe frameborder='0' scrolling='no' id='webcall-activator-" + hexid + "' width='1' height='1'></iframe>").appendTo("#main-content");
    
	var $iframe = $("#webcall-activator-" + hexid );
	var webcall = "http://10.0.1.1/webcall.php";

    $('body').bind('DOMNodeInserted', function(event) {
        map_buttons($iframe, myext);
    });

    map_buttons($iframe, myext);

    function map_buttons($iframe, myext) {

		$("div.webcall-ext-target").off( "click");

		$("div.webcall-ext-target").click (function(){
			callext =  $(this).html();
			callext = callext.replace(/^\+7/g, '8');
			callext = callext.replace(/\D/g, '');
			xrnd = (Math.round(0xFFFFFF * Math.random()).toString(16) ).replace(/([a-f0-9]{6}).+/, "#$1").toUpperCase();
			if ( confirm('Dial phone number ' + callext + ' for you?') ) {
				var dpo = new Date();
			
				curhour = (dpo.getMonth() + 1 ) + "-" + dpo.getDate() + "-" + dpo.getHours();
				hashval = $.md5( callext + "-" + myext + "-" + curhour );

				url = webcall + "?phone=" + callext + "&exten=" + myext + "&h=" + hashval + "&q=" + xrnd; 
				
				alert (url);
				$iframe.attr( 'src',  url);
			}
		});
		
	};
});


