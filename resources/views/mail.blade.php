<div class="container" style = "width: 600px;padding: 20px;background: #f4f4f4;">
<div class = "image" style = "">

  <img src="http://naseni.cayandemo.com/cayanLogo.png" alt="Logo" title="Logo" style="display:block;margin-bottom: 20px" width="200" height="60"  >
</div>

<div class = "wrapper" style = "padding: 20px;background: white;">
<h2>Dear, {{ $name }}</h2>
<?php if($code!='' && $code !=null){ ?>
 
 <div style="">
    <p>New Login Attempt at Naseni Certveri portal.</p>
    <p>To verify your identity, use the code below within the next 5 minutes and finish logging in to your account.</p>
    <h2 style="background: #1a6f45;color: white;text-align: center;">{{$code}} </h2>
 </div>
<?php
}
if($userName!='' && $userName !=null){ 
?>
<div style = "">
<p>Thanks for Signing Up <br> Below are your Credentials</p>
<br>
<p>Login URL : 
    <a href={{$url}}>
        {{$url}}
    </a>
</p>
<p>Email : {{$userName}} </p>
<p>Password : {{$password}} </p>
</div>
<?php
}
?>
<div class = "footer">
    <p>In case of any questions, contact our support through email pm@cayanjo.com.</p>
    <p>Thank you, <br>Naseni Certveri.</p>
</div>

</div>

</div>