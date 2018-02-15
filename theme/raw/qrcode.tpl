<div id="qrcode_{$blockid}" align="{$align}"></div>
<script type="application/javascript">
    var el = kjua({$kjuaconfobject|safe});
    $j('#qrcode_{$blockid}').append(el);
</script>