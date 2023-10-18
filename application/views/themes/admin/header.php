<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="<?=base_url()?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Panel - Alma de las cosas</title>
    <meta name="description" content="System App Alma de las Cosas - Regalos y más.">
    <meta name="author" content="Alma de las cosas Colombia">
    <link rel="icon" type="image/x-icon" href="assets/img/icon.jpg"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/general-style.css?<?=rand()?>">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link loading="lazy" rel="stylesheet" href="assets/css/toastr.min.css">

    <?php
    //If CSS Data exists, load files
    if(isset($css_data)){
        foreach($css_data AS $css_file) echo '<link href="'.$css_file.'" rel="stylesheet">';
    }
    if(isset($map_js)){ ?> <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrAPV7f9BNJu-WKXlfcsFRQOJGShZ2eQI&callback=initMap"></script> <?php } ?>
    <script language="javascript">
        var base_url = "<?=base_url();?>";
    </script>
</head>
<body>

<div class="mask mask-semi" style="display:none;" id="mask-semi">
    <div class="mask-position">
        <img class="" src="assets/img/001-rocking-horse.png" alt="Loading...">
    </div>
</div>
<div class="mask mask-normal" id="mask-semi">
    <div class="mask-position">
        <img class="" src="assets/img/001-rocking-horse.png" alt="Loading...">
    </div>
</div>
