<html>
    <style>
        h1,
        p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .app {
            width: 90%;
            max-width: 500px;
            margin: 0 auto;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .item {
            width: 90px;
            height: 90px;
            display: flex;
            justify-content: center;
            align-items: center;
            user-select: none;
        }
        .radio {
            display: none;
        }
        .radio ~ span {
            font-size: 3rem;
            filter: grayscale(0);
            cursor: pointer;
            transition: 0.3s;
        }

        
    </style>
<body>
<p>Hello,</p><p>You submitted a file on ECU tech portal (#file_name). We will be grateful if you will provide your feedback by just clicking over these emojis. </p>
<div style="text-align: center;">
    <div class="container">
        <div class="item">
          <label for="0">
          <input class="radio" type="radio" name="feedback" id="0" value="0">
          <span><a href="#angry_link" style="color:white;">🤬</a></span>
        </label>
        </div>
    
        <div class="item">
          <label for="1">
          <input class="radio" type="radio" name="feedback" id="1" value="1">
          <span><a href="#sad_link" style="color:white;">🙁</a></span>
        </label>
        </div>
    
        <div class="item">
          <label for="2">
          <input class="radio" type="radio" name="feedback" id="2" value="2">
          <span><a href="#ok_link" style="color:white;">😶</a></span>
        </label>
        </div>
    
        <div class="item">
          <label for="3">
          <input class="radio" type="radio" name="feedback" id="3" value="3">
          <span><a href="#good_link" style="color:white;">😁</a></span>
        </label>
        </div>
    
        <div class="item">
          <label for="4">
          <input class="radio" type="radio" name="feedback" id="4" value="4">
          <span><a href="#happy_link" style="color:white;">😍</a></span>
        </label>
        </div>
    
      </div>
</div>
<p>Or please login and provide your feeback by clicking <a href="#file_url">here</a>.</p>
</body>
</html>