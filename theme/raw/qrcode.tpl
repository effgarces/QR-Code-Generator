<script type="text/javascript" src="{$WWWROOT}blocktype/qrcode/js/jquery.qrcode.min.js"></script>
<div id="qrcode_{$blockid}" align="{$align}"></div>
<script type="application/javascript">
    var w = '{$width}';
    var h = '{$height}';
    var qr = unescape(decodeURIComponent('{$qrcode}'));
    var node = '#qrcode_{$blockid}';
    {literal}$j(node).qrcode({width: w,height: h,text: qr});{/literal}
</script>