<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Global Power Group</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=asset('css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=asset('css/bootstrap-reset.css')?>" rel="stylesheet">
    <!--external css-->
    <link href="<?=asset('assets/font-awesome/css/font-awesome.css')?>" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?=asset('css/style.css')?>" rel="stylesheet">
    <link href="<?=asset('css/gpgstyles.css')?>" rel="stylesheet">
    <link href="<?=asset('css/style-responsive.css')?>" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="login-body">

    <div class="container">

    <!-- Content -->
    @yield("login_content")

    </div>



    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?=asset('js/jquery.js')?>"></script>
    <script src="<?=asset('js/bootstrap.min.js')?>"></script>


  </body>
</html>
