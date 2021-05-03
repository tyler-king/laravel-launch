<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon" />
    <title>Linking</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
	<script>
					
		onTest =  function(){
			//window.location.href = '{{ $location_deep }}'
			//window.location.href = '{{ $location_expo }}'
			setTimeout(function(){
				redirectStores('{{ $id }}')
			},500)
		}

		var redirectStores = function(id){

			console.log(navigator.userAgent.toLowerCase());

			if(navigator.userAgent.toLowerCase().indexOf('android') > -1){

				console.log('Android');
				//window.location.href = 'https://play.google.com/store/apps/details?id=' + id;

			}else if(navigator.userAgent.toLowerCase().indexOf('iphone') > -1){

				console.log('iOS');
				//window.location.href = 'https://itunes.apple.com/us/app/' + id;

			}else{

				console.log('Other');
				//window.location.href = 'https://www.example.com';

			}
		}
		
	</script>
    <!-- Styles -->
    <style>
        html,
        body {
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

        .links>a {
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
    </style>
</head>

<body onload='onTest()'>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                Linking to {{ $app }}
            </div>
        </div>
    </div>
</body>

</html>