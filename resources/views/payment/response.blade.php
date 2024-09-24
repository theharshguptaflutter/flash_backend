<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body{
    margin: 0;
    }
    .after-payment-screen{
        overflow: hidden;
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .after-payment-screen figure{
        margin: 0;
        text-align: center;
        display: flex;
        align-items: center;
        flex-direction: column;
    }
    .after-payment-screen figure .image-container{
        font-size: 50px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: green;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }
    .after-payment-screen figure figcaption{
        color: green;
        font-size: 40px;
        text-transform: uppercase;
        font-weight: 600;
        padding-top: 10px;
    }
    .after-payment-screen figure.unsuccessful .image-container{
        background: red;
    }
    .after-payment-screen figure.unsuccessful figcaption{
        color: red;
    }
</style>
</head>
	<body>
		<section class="after-payment-screen">
			   <!-- for successful -->
				<figure>
					<div class="image-container">
						<i class="fa fa-check" aria-hidden="true"></i>
					</div>
                    <h1> The payment status is: </h1> 
						<figcaption>{{$transaction->transaction_status}}</figcaption>
                        
				</figure>
			
		</section>

	</body>
</html>