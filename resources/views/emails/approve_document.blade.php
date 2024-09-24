<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<title>Approve Document</title>

</head>
<body>
	<table style="width: 600px;margin: 0 auto;border:1px solid #ccc;background: #fff;border-collapse: collapse;font-family: 'Roboto', sans-serif;">
		<tr>
			<td>
				<table style="width: 100%;border-bottom: 1px solid #34576D;background-color:#fff;">
					<tr>
						<td style="padding: 15px 40px;" align="center">
						<img src="<?php echo config('constant.S3_PATH')?>logo/logo_1659962130163.jpg" width="200px">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td style="padding: 15px 40px;">
							<p style="font-size: 16px;line-height: 24px;">Dear {{ $content['fullName'] }},</p>
							<p style="font-size: 16px;line-height: 24px;">
                                <!--Your account is successfully approved you can go and log in now.--->
                                <b>{{ $content['msg'] }}</b>.
							</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
		<td style="padding: 15px 40px;">
			<p style="font-size: 16px;line-height: 24px;">Thanks & Regards,</p>
			<p style="font-size: 16px;line-height: 24px;">Flash Team</p> 
			</td>
			<!-- <td align="center">
				<a href="javascript:;" style="display: inline-block;font-size: 15px;height: 45px;border-radius: 100px;padding: 0px 50px;
                background: #34576D;color: #FFF;border-color: #26af3e;text-decoration: none;line-height: 45px;">Click Here to Log In</a>
			</td> -->
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
		
	</table>
</body>
</html>