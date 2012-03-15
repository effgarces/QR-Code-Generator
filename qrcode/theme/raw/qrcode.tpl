{if $mode == 'edit'}
<div id="qrcode" align="{$align}">
	<div class="description">{str tag=previewonly section=blocktype.qrcode}</div>
	<img src="{$WWWROOT}blocktype/qrcode/theme/raw/static/images/preview.png">
</div>
{elseif $qrcode != ''}
{literal}
<script language="javascript" src="http://www.google.com/jsapi"></script>
   <div id="qrcode" align="{/literal}{$align}{literal}"></div>
   <script type="text/javascript">
      var queryString = '';
      var dataUrl = '';

      function onLoadCallback() {
        if (dataUrl.length > 0) {
          var query = new google.visualization.Query(dataUrl);
          query.setQuery(queryString);
          query.send(handleQueryResponse);
        } else {
          var dataTable = new google.visualization.DataTable();
          
          draw(dataTable);
        }
      }
      	  
      function draw(dataTable) {
        var vis = new google.visualization.ImageChart(document.getElementById('qrcode'));
        var options = {
          chs: '{/literal}{$size}{literal}',
          cht: 'qr',
          chld: '',
		  choe: 'UTF-8',
          chl: unescape(decodeURIComponent('{/literal}{$qrcode}{literal}')),
        };
        vis.draw(dataTable, options);
      }

      function handleQueryResponse(response) {
        if (response.isError()) {
          alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
          return;
        }
        draw(response.getDataTable());
      }

      google.load("visualization", "1", {packages:["imagechart"]});
      google.setOnLoadCallback(onLoadCallback);

    </script>
	{/literal}
{/if}