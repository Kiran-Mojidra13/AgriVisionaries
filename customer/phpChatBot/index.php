<?php
date_default_timezone_set('Asia/Kolkata');
include('database.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="robots" content="noindex, nofollow">
      <title>PHP Chatbot</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
      <link href="style.css" rel="stylesheet">
      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
   </head>
   <body>
      <div class="container">
         <div class="row justify-content-md-center mb-4">
            <div class="col-md-6">
               <!--start code-->
               <div class="card">
                  <div class="card-body messages-box">
                     <ul class="list-unstyled messages-list">
                        <!-- Start of the message display loop -->
                        <li class="messages-me clearfix start_chat">
                           Please start your conversation by typing a message.
                        </li>
                     </ul>
                  </div>
                  <div class="card-header">
                    <div class="input-group">
                       <input id="input-me" type="text" name="messages" class="form-control input-sm" placeholder="Type your message here..." />
                       <span class="input-group-append">
                       <input type="button" class="btn btn-primary" value="Send" onclick="send_msg()">
                       </span>
                    </div> 
                  </div>
               </div>
               <!--end code-->
            </div>
         </div>
      </div>

      <script type="text/javascript">
         function getCurrentTime(){
            var now = new Date();
            var hh = now.getHours();
            var min = now.getMinutes();
            var ampm = (hh>=12)?'PM':'AM';
            hh = hh%12;
            hh = hh?hh:12;
            hh = hh<10?'0'+hh:hh;
            min = min<10?'0'+min:min;
            var time = hh+":"+min+" "+ampm;
            return time;
         }

         function send_msg(){
            jQuery('.start_chat').hide();
            var txt=jQuery('#input-me').val();
            var html='<li class="messages-me clearfix"><span class="message-img"><img src="image/user_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Me</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'+getCurrentTime()+'</span></small> </div><p class="messages-p">'+txt+'</p></div></li>';
            jQuery('.messages-list').append(html);
            jQuery('#input-me').val('');
            
            if(txt){
                jQuery.ajax({
                    url:'get_bot_message.php',
                    type:'post',
                    data:'txt='+txt,
                    success:function(result){
                        var html='<li class="messages-you clearfix"><span class="message-img"><img src="image/bot_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Chatbot</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'+getCurrentTime()+'</span></small> </div><p class="messages-p">'+result+'</p></div></li>';
                        jQuery('.messages-list').append(html);
                        jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight);
                    }
                });
            }
         }
      </script>
   </body>
</html>
