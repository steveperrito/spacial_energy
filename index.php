<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/main.css">
  <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
  your browser</a> to improve your experience.</p>
<![endif]-->

<div class="container">
  <div class="row top-row file-row">
    <div class="col-sm-6 col-sm-offset-3 text-center bordered">
      <div class="file-form">
        <form class="form-inline text-left" enctype="multipart/form-data" action="csv-to-table.php" method="post">
          <input type="hidden" name="MAX_FILE_SIZE" value="30000"/>

          <div class="form-group">
            <label for="csv-upload">Upload File:</label>
            <input name="csv-upload" type="file" id="csv-upload" accept=".csv"/>

            <p class="help-block">CSV files only.</p>
          </div>
          <button type="submit" class="btn btn-default file-sub-btn">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
<script src="js/plugins.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>