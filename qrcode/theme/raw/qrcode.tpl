   <script type="text/javascript" src="{$WWWROOT}blocktype/qrcode/js/jquery.qrcode.min.js"></script>
   <div id="qrcode" align="{$align}"></div>
   <script type="text/javascript">
      var w = "{$width}";
	  var h = "{$height}";
	  var qr = unescape(decodeURIComponent('{$qrcode}'))
      {literal}$j('#qrcode').qrcode({width: w,height: h,text: qr});{/literal}
   </script>
