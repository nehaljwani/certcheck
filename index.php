<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script>
    var colorMap = ["#00ff40", "#00ff00", "#40ff00", "#80ff00", "#bfff00", "#ffff00", "#ffbf00", "#ff8000", "#ff4000", "#ff0000"];

    var set_time_left = function(cell, date_future) {
        setInterval(function() {
            var delta = Math.abs(date_future - (Date.now() / 1000));
            var days = Math.floor(delta / 86400);
            delta -= days * 86400;
            var hours = Math.floor(delta / 3600) % 24;
            delta -= hours * 3600;
            var minutes = Math.floor(delta / 60) % 60;
            delta -= minutes * 60;
            var seconds = Math.floor(delta % 60);
            cell.text(days + "d " + hours + "h " + minutes + "m " + seconds + "s");
        }, 1000);
    }

    var set_time_spent = function(progress_bar, valid_from, valid_to) {
        setInterval(function() {
            var total_time = valid_to - valid_from;
            var time_spent = Math.floor(Date.now() / 1000) - valid_from;
            var percentage = Math.floor((time_spent * 100) / total_time);
            percentage = percentage > 100 ? 100 : percentage;
            progress_bar.text(percentage + "%");
            progress_bar.css("color", "#000000");
            progress_bar.css("width", percentage + "%");
            progress_bar.attr("aria-valuenow", percentage);
            progress_bar.css("background-color", colorMap[Math.floor(percentage / 10)]);
        }, 1000);
    }

    $(document).ready(function() {
        $(".dom-btn").click(function() {
	    var row = $(this).closest("tr");
            var fqdn = row.find("td.fqdn").text();
            var port = row.find("td.port").text();
            var issuer = row.find("td.issuer");
            var time_left = row.find("td.timeleft");
            var loading = row.find("img.loading");
	    var progress = row.find("div.progress");
	    var progress_bar = row.find("div.progress-bar");
	    $.ajax({
		url: '/check.php',
		method: "POST",
		data: { fqdn: fqdn, port: port },
                beforeSend: function() {
                    progress.hide();
                    loading.show();
                },
		complete: function(reply) {
                    loading.hide();
                    result = $.parseJSON(reply.responseText)
                    issuer.text(result["issuer"]["CN"]);
                    set_time_left(time_left, result['validTo_time_t']);
                    set_time_spent(progress_bar, result['validFrom_time_t'], result['validTo_time_t']);
                    progress.show();
		    $(".table").append($(".entries").get().sort(function(a, b) {
			var diff = parseInt($(b).find(".progress-bar").attr("aria-valuenow")) - parseInt($(a).find(".progress-bar").attr("aria-valuenow"));
			return diff ? diff : false;
		    }));
                }
	    });
        });
    });
  </script>
</head>
<body>

<?php
    $domains_info = explode("\n", trim(file_get_contents('domains.txt')));
    $admin_url = "/admin";
?>
<div class="container">
    <h2>Certificates Info</h2>
    <p>Click <a onClick="javascript: $('.btn').click();" href='#'>here</a> to check all.</p>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Fully Qualified Domain Name</th>
          <th>SSL Port</th>
          <th>SSL Certificate Issuer</th>
          <th>♥ Life Spent</th>
          <th>☠ Time Left</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
<?php
    foreach ($domains_info as $index=>$info) {
          $domain_info = explode(":", $info);
          $fqdn = $domain_info[0];
	  $port = $domain_info[1];
?>
          <tr class="entries">
          <td class="fqdn"><?php echo $fqdn ?></td>
          <td class="port"><?php echo $port ?></td>
          <td class="issuer"></td>
          <td>
              <img class="loading" src="loading.gif" alt="Loading!" style="display: none"/>
              <div class="progress" style="display: none">
                <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
          </td>
          <td class="timeleft"></td>
          <td>
              <input class="btn btn-secondary dom-btn" type="button" value="Check">
          </td>
          </tr>
<?php
    }
?>
      </tbody>
    </table>
</div>

</body>
</html>
