<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body{
          margin-top: 150px;
          background-color: #C4CCD9;
        }
        .error-main{
          background-color: #fff;
          box-shadow: 0px 10px 10px -10px #5D6572;
        }
        .error-main h1{
          font-weight: bold;
          color: #444444;
          font-size: 140px;
          text-shadow: 2px 4px 5px #6E6E6E;
        }
        .server {
          font-size: 30px;
        }
        .error-main h6{
          color: #42494F;
          font-size: 20px;
        }
        .error-main p{
          color: #9897A0;
          font-size: 15px; 
        }
    </style>
</head>
<body>
  
    <div class="container">
      <div class="row text-center">
        <div class="col-lg-6 offset-lg-3 col-sm-6 offset-sm-3 col-12 p-3 error-main">
          <div class="row">
            <div class="col-lg-8 col-12 col-sm-10 offset-lg-2 offset-sm-1">
              <h1 class="m-0">503</h1>
              <h4><span class="server" style="margin-right:6px">Server Maintenance</span>  | <img src="{{asset('img/gear-set.gif')}}" alt="Warning" style="margin-left:2px"></h4>
              <p><span class="text-info">DAM</span> currently in <span class="text-info">maintenance mode</span>. We will be back <span class="text-info">soon.</span></p>
              <p><span class="text-info">Thank you for understanding.</span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
      
</body>
</html>