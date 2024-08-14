<link href="<?=base_url()?>/css/nivo-slider.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/swfobject.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.tinyTips.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.editinplace.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.mousewheel-3.0.2.pack.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.fancybox-1.3.1.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.maskedinput-1.2.2.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.watermarkinput.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.clearform.js"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/jquery.timers.js"></script>
<script src="<?=base_url()?>javascript/Scripts/jquery.elastic-1.6.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?=base_url()?>javascript/Scripts/ajaxupload.js"></script>
<script type="text/javascript">

      var fadeOpacity  = new Array();
      var fadeTimer    = new Array();
      var fadeInterval = 100;  // milliseconds

      function fade(o,d)
      {

        // o - Object to fade in or out.
        // d - Display, true =  fade in, false = fade out

        var obj = document.getElementById(o);

        if((fadeTimer[o])||(d&&obj.style.display!='block')||(!d&&obj.style.display=='block'))
        {

          if(fadeTimer[o])
            clearInterval(fadeTimer[o]);
          else
            if(d) fadeOpacity[o] = 0;
            else  fadeOpacity[o] = 9;

          obj.style.opacity = "."+fadeOpacity[o].toString();
          obj.style.filter  = "alpha(opacity="+fadeOpacity[o].toString()+"0)";

          if(d)
          {
            obj.style.display = 'block';
            fadeTimer[o] = setInterval('fadeAnimation("'+o+'",1);',fadeInterval);
          }
          else
            fadeTimer[o] = setInterval('fadeAnimation("'+o+'",-1);',fadeInterval);
        }
      }

      function fadeAnimation(o,i)
      {

        // o - o - Object to fade in or out.
        // i - increment, 1 = Fade In

        var obj = document.getElementById(o);
        fadeOpacity[o] += i;
        obj.style.opacity = "."+fadeOpacity[o].toString();
        obj.style.filter  = "alpha(opacity="+fadeOpacity[o].toString()+"0)";

        if((fadeOpacity[o]=='9')|(fadeOpacity[o]=='0'))
        {
          if(fadeOpacity[o]=='0')
            obj.style.display = 'none';
          else
          {
            obj.style.opacity = "1";
            obj.style.filter  = "alpha(opacity=100)";
          }

          clearInterval(fadeTimer[o]);
          delete(fadeTimer[o]);
          delete(fadeTimer[o]);
          delete(fadeOpacity[o]);

        }
      }

    </script>
	<script type="text/javascript">
	$(window).load(function() {
		setTimeout(function() {
		$('#slider1').nivoSlider({pauseTime:5000, pauseOnHover:false, controlNav:false});	
		}, 1000);
	});
	</script>
<div id="slider1" class="nivoSlider">
<img src="<?=base_url()?>images/img1.jpg" alt="" width="598" height="341" />
<img src="<?=base_url()?>images/img2.jpg" alt="" width="598" height="341" />
<img src="<?=base_url()?>images/img3.jpg" alt="" width="598" height="341" />
<img src="<?=base_url()?>images/img4.jpg" alt="" width="598" height="341" />
<img src="<?=base_url()?>images/img5.jpg" alt="" width="598" height="341" />
<img src="<?=base_url()?>images/img6.jpg" alt="" width="598" height="341" />

