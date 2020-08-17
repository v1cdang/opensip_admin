<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Opensip Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
     <!-- Fonts -->
     <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

     <!-- Styles -->
     <style>
         html, body {
             background-color: #fff;
             color: #636b6f;
             font-family: 'Nunito', sans-serif;
             font-weight: 200;
             height: 100vh;
             margin: 0;
         }

         .full-height {
             height: 100vh;
         }

         .flex-center {
             align-items: center;
             display: flex;
             justify-content: center;
         }

         .position-ref {
             position: relative;
         }

         .top-right {
             position: absolute;
             right: 10px;
             top: 18px;
         }

         .content {
             text-align: center;
         }

         .title {
             font-size: 84px;
         }

         .links > a {
             color: #636b6f;
             padding: 0 25px;
             font-size: 13px;
             font-weight: 600;
             letter-spacing: .1rem;
             text-decoration: none;
             text-transform: uppercase;
         }

         .m-b-md {
             margin-bottom: 30px;
         }
         .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: black;
            color: white;
            text-align: center;
            }
     </style>
     <script src="http://code.jquery.com/jquery-3.3.1.min.js"
     integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
     crossorigin="anonymous">
</script>
<script>
        jQuery(document).ready(function(){
            $('#customerPrefix').on('change', function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var urlg = "/getChildExtension/"+ $(this).val();
                jQuery.ajax({

                    url: urlg,
                    method: 'get',
                    success: function(result) {
                        var extensions = result.split("|");

                        jQuery.each(extensions, function(key, extension){
                            console.log(extension);
                            $('#destinationExt').append($('<option>', {
                                value: extension,
                                text: extension
                            }));
                        })
                    }
                });
                var urlg = "/getCustomerDID/"+ $(this).val();
                jQuery.ajax({

                    url: urlg,
                    method: 'get',
                    success: function(result) {
                        var DIDs = result.split("|");

                        jQuery.each(DIDs, function(key, DID){
                            console.log(DID);
                            $('#DID').append($('<option>', {
                                value: DID,
                                text: DID
                            }));
                        })
                    }
                });
            });
        });
</script>
</head>

<body>
@include('includes.header')

<div class="container">
    @yield('content')
</div>

<footer class="footer">
    <div class="container">
        @include('includes.footer')
    </div>
</footer>

<script>
    $(function(){
      $('#prefixSelect').on('change', function () {
            var id = $(this).val(); // get selected value
            if (id) {
                window.location =id;
            }
            return false;
      });
    });
</script>
</body>
</html>
