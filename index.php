<?php
$isLogin = false;
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login = json_decode(file_get_contents("login.json"));
    if ($login->username == $username && $login->password == $password) {
        $isLogin = true;
    }
}
if ($isLogin) :
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100vw;
                height: 100vh;
                perspective: 100vmin;
                overflow: hidden;
            }

            body * {
                box-sizing: border-box;
                outline: none;
                transition: all 0.5s ease 0s;
            }

            .container2 {
                position: fixed;
                width: 100vw;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                top: 0;
                left: 0;
                overflow: hidden;
            }

            .box {
                width: 36vmin;
                height: 44vmin;
                transform-style: preserve-3d;
                position: absolute;
                box-shadow: 0.15vmin 0 0.5vmin -0.25vmin #c5c5c5;
                z-index: 0;
                border-radius: 3vmin;
                transform: rotateY(30deg);
                background: #3c3c3c;
            }

            .box:after {
                content: "";
                background: #212121;
                width: 98%;
                height: 98%;
                position: absolute;
                left: 1%;
                transform: translateZ(-1px);
                top: 1%;
                border-radius: 3vmin;
                transition: all 0.5s ease 0s;
            }

            #switch:checked+.box {
                background: transparent;
            }

            #switch:checked+.box label {
                background: #e6e6e6;
            }

            #switch:checked~.box:after {
                background: #e9e9e9;
                transition: all 0.5s ease 0s;
            }

            #switch {
                display: none;
            }

            label {
                float: left;
                width: 100%;
                height: 100%;
                box-sizing: border-box;
                border-radius: 3vmin;
                padding: 0.25vmin;
                transform-style: preserve-3d;
                box-shadow: 0 0 0 1vmin #00000020 inset;
                cursor: pointer;
            }

            label:before {
                content: "";
                width: 10.5vmin;
                height: 20vmin;
                position: absolute;
                background: #333;
                z-index: 0;
                border-radius: 1vmin;
                left: 12.75vmin;
                top: 12vmin;
                box-sizing: border-box;
                box-shadow: -5px 0px 5px 0px #00000080 inset;
                transform: translateZ(1px);
            }

            label .on:before,
            label .on:after {
                content: "";
                width: 2vmin;
                height: 0.15vmin;
                position: absolute;
                top: 95%;
                left: 5vmin;
                border-radius: 100%;
                transform: rotateZ(0deg) rotateY(1deg) translate3d(0px, 0px, 1px);
                background: #3c3c3c;
                border-top: 1vmin solid #343434;
                border-bottom: 1vmin solid #343434;
                box-shadow: -1px -1px 1px 0px #23232380;
                transition: all 0.5s ease 0s;
            }

            label .on:after {
                transform: rotateZ(0deg) rotateY(-1deg) translate3d(23vmin, 0px, 1px);
            }

            #switch:checked+.box label .on:before,
            #switch:checked+.box label .on:after {
                background: #eaeaea;
                border-top: 1vmin solid #a5a5a5;
                border-bottom: 1vmin solid #a5a5a5;
                box-shadow: -1px -1px 1px 0px #ffffff80;
                transition: all 0.5s ease 0s;
            }

            label span {
                float: left;
                width: 100%;
                height: 50%;
                border-radius: 3vmin 3vmin 0 0;
                text-align: center;
                padding: 2vmin;
                perspective: 100vmin;
                transform-style: preserve-3d;
                font-family: Arial, Helvetica, serif;
                font-size: 4.5vmin;
                font-weight: bold;
                color: #4caf5040;
                text-shadow: 1px 1px 1px white;
                color: #ffffff12;
                text-shadow: -1px -1px 1px #00000080;
            }

            label .off {
                border-radius: 0 0 3vmin 3vmin;
                text-align: center;
                padding-top: 14vmin;
                color: #ff4d4d1f;
                transform: translateZ(1px);
            }

            #switch:checked+.box label span {
                color: #4caf50f5;
                text-shadow: 1px 1px 1px white;
            }

            #switch:checked+.box label .off {
                color: #ff000040;
                text-shadow: 1px 1px 1px white;
            }

            .cube-switch {
                border-radius: 0;
                position: absolute;
                left: 0;
                top: 18vmin;
                height: 8vmin;
                padding: 0;
                width: 8vmin;
                left: 14vmin;
                z-index: 0;
                transform-style: preserve-3d;
                perspective-origin: bottom;
                transform: translate3d(0, 0, -4vmin) rotateX(140deg);
                transform-origin: center center;
            }

            #switch:checked+.box label .cube-switch {
                transform: translate3d(0, 0, -4vmin) rotateX(220deg);
            }

            label .cube-switch span {
                position: absolute;
                width: 200%;
                height: 100%;
                background: #333333;
                border: 1px solid #31313166;
                border-radius: 0.5vmin;
            }

            label .cube-switch span:nth-child(1) {
                background: linear-gradient(160deg, #333 0%, #333 23%, #121212);
                transform: rotateX(0deg) translate3d(-4vmin, 0vmin, -14vmin);
                width: 100%;
            }

            label .cube-switch span:nth-child(2) {
                transform: rotateY(0deg) translate3d(-4vmin, 0vmin, 2vmin);
                width: 100%;
            }

            label .cube-switch span:nth-child(3) {
                background: linear-gradient(180deg, #333 0%, #333 50%, #222);
                transform: rotateX(-90deg) translate3d(-4vmin, 6vmin, 0vmin);
                width: 100%;
                height: 200%;
            }

            label .cube-switch span:nth-child(4) {
                transform: rotateY(90deg) translate3d(6vmin, 0vmin, -4vmin);
            }

            label .cube-switch span:nth-child(5) {
                background: linear-gradient(125deg, #333 0%, #333 50%, #222);
                transform: rotateY(90deg) translate3d(6vmin, 0vmin, -12vmin);
            }

            label .cube-switch span:nth-child(6) {
                transform: rotateX(-90deg) translate3d(-4vmin, 6vmin, -8vmin);
                width: 100%;
                height: 200%;
            }

            #switch:checked+.box label .cube-switch span:nth-child(1) {
                background: linear-gradient(125deg, #fff 0%, #bdbdbd 50%, #6f6f6f);
            }

            #switch:checked+.box label .cube-switch span:nth-child(3) {
                background: linear-gradient(125deg, #333 0%, #333 50%, #222);
            }

            #switch:checked+.box label .cube-switch span:nth-child(5) {
                background: linear-gradient(60deg, #333 0%, #333 50%, #505050);
            }

            #switch:checked+.box label .cube-switch span:nth-child(6) {
                background: linear-gradient(199deg, #333 0%, #333 40%, #8e8e8e);
            }

            #turn {
                position: absolute;
                bottom: 4vmin;
                left: 3vw;
                width: 94vw;
            }

            #switch:checked~#turn {
                background: #ffffff;
            }

            .turned-off {
                background: #000000e0;
                position: absolute;
                left: 0;
                top: 0;
                width: 100vw;
                height: 100vh;
                z-index: -1;
            }

            #switch:checked~.turned-off {
                background: transparent;
            }

            .backside {
                background: #2b2b2b;
                width: 100%;
                height: 100%;
                position: absolute;
                transform: translateZ(-2vmin);
                border-radius: 3vmin;
            }

            #switch:checked+.box .backside {
                background: #afafaf;
            }


            /*** RANGE STYLES ***/

            input[type=range] {
                background: #1f1f1f;
            }

            input[type=range]:hover {
                cursor: pointer;
            }

            input[type=range]:focus {
                outline: none;
            }

            input[type=range],
            input[type=range]::-webkit-slider-runnable-track,
            input[type=range]::-webkit-slider-thumb {
                -webkit-appearance: none;
            }

            input[type=range]::-webkit-slider-thumb {
                background-color: #3c3c3c;
                width: 4vmin;
                height: 4vmin;
                border: 2px solid #1f1f1f;
                border-radius: 50%;
                margin-top: -2vmin;
                transition: all 0.5s ease 0s;
            }

            input[type=range]::-moz-range-thumb {
                background-color: #3c3c3c;
                width: 4vmin;
                height: 4vmin;
                border: 2px solid #1f1f1f;
                border-radius: 50%;
                transition: all 0.5s ease 0s;
            }

            input[type=range]::-ms-thumb {
                background-color: #3c3c3c;
                width: 4vmin;
                height: 4vmin;
                border: 2px solid #1f1f1f;
                border-radius: 50;
                transition: all 0.5s ease 0s;
            }

            input[type=range]::-webkit-slider-runnable-track {
                background-color: #3c3c3c;
                transition: all 0.5s ease 0s;
                height: 3px;
            }

            input[type=range]:focus::-webkit-slider-runnable-track {
                outline: none;
            }

            input[type=range]::-moz-range-track {
                background-color: #3c3c3c;
                transition: all 0.5s ease 0s;
                height: 3px;
                border-color: red;
            }

            input[type=range]::-ms-track {
                background-color: #3c3c3c;
                transition: all 0.5s ease 0s;
                height: 3px;
            }

            input[type=range]::-ms-fill-lower {
                background-color: #ff6347
            }

            input[type=range]::-ms-fill-upper {
                background-color: black;
            }


            #switch:checked~input[type=range]::-webkit-slider-thumb {
                border-color: #fff;
                background: #c9c9c9;
                transition: all 0.5s ease 0s;
            }

            #switch:checked~input[type=range]::-moz-range-thumb {
                border-color: #fff;
                background: #c9c9c9;
                transition: all 0.5s ease 0s;
            }

            #switch:checked~input[type=range]::-ms-thumb {
                border-color: #fff;
                background: #c9c9c9;
                transition: all 0.5s ease 0s;
            }


            #switch:checked~input[type=range]::-webkit-slider-runnable-track {
                background-color: #c9c9c9;
                transition: all 0.5s ease 0s;
            }

            #switch:checked~input[type=range]::-moz-range-track {
                background-color: #c9c9c9;
                transition: all 0.5s ease 0s;
            }

            #switch:checked~input[type=range]::-ms-track {
                background-color: #c9c9c9;
                transition: all 0.5s ease 0s;
            }
        </style>
        <title>Lamp</title>
    </head>
    <script>
        function spinSwitch() {
            document.getElementById("box").style.transform =
                "rotateY(" + document.getElementById("turn").value + "deg)";
        }

        function getState() {
            var xhttp = new XMLHttpRequest();
            var checkbox = document.getElementById("switch");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Typical action to be performed when the document is ready:
                    if (xhttp.responseText == "true")
                        checkbox.removeAttribute("checked");
                    else
                        checkbox.setAttribute("checked", true);
                }
            };
            xhttp.open("GET", "process.php?getState", true);
            xhttp.send();
        }

        function setState() {
            var state = document.getElementById("switch").checked;
            var xhttp = new XMLHttpRequest();
            xhttp.open("GET", "process.php?setState=" + state, true);
            xhttp.send();
        }
    </script>

    <body onload="getState()">
        <div class="container2">
            <input id="switch" type="checkbox" onchange="setState()">
            <div class="box" id="box">
                <div class="backside"></div>
                <label for="switch">
                    <span class="on">ON</span>
                    <span class="off">OFF</span>
                    <span class="cube-switch">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </label>
            </div>
            <div class="turned-off"></div>
            <input type="range" id="turn" min="0" max="360" value="30" oninput="spinSwitch()" onchange="spinSwitch()">
        </div>
    </body>

    </html>

<?php endif;
if (!$isLogin) : ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <style>
            body {
                background: #222D32;
                font-family: 'Roboto', sans-serif;
            }

            .login-box {
                margin-top: 75px;
                height: auto;
                background: #1A2226;
                text-align: center;
                box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
                padding: 1em;
            }

            .login-key {
                height: 100px;
                font-size: 80px;
                line-height: 100px;
                background: -webkit-linear-gradient(#27EF9F, #0DB8DE);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .login-title {
                margin-top: 15px;
                text-align: center;
                font-size: 30px;
                letter-spacing: 2px;
                margin-top: 15px;
                font-weight: bold;
                color: #ECF0F5;
            }

            .login-form {
                margin-top: 25px;
                text-align: left;
            }

            input[type=text] {
                background-color: #1A2226;
                border: none;
                border-bottom: 2px solid #0DB8DE;
                border-top: 0px;
                border-radius: 0px;
                font-weight: bold;
                outline: 0;
                margin-bottom: 20px;
                padding-left: 0px;
                color: #ECF0F5;
            }

            input[type=password] {
                background-color: #1A2226;
                border: none;
                border-bottom: 2px solid #0DB8DE;
                border-top: 0px;
                border-radius: 0px;
                font-weight: bold;
                outline: 0;
                padding-left: 0px;
                margin-bottom: 20px;
                color: #ECF0F5;
            }

            .form-group {
                margin-bottom: 40px;
                outline: 0px;
            }

            .form-control:focus {
                border-color: inherit;
                -webkit-box-shadow: none;
                box-shadow: none;
                border-bottom: 2px solid #0DB8DE;
                outline: 0;
                background-color: #1A2226;
                color: #ECF0F5;
            }

            input:focus {
                outline: none;
                box-shadow: 0 0 0;
            }

            label {
                margin-bottom: 0px;
            }

            .form-control-label {
                font-size: 10px;
                color: #6C6C6C;
                font-weight: bold;
                letter-spacing: 1px;
            }

            .btn-outline-primary {
                border-color: #0DB8DE;
                color: #0DB8DE;
                border-radius: 0px;
                font-weight: bold;
                letter-spacing: 1px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            }

            .btn-outline-primary:hover {
                background-color: #0DB8DE;
                right: 0px;
            }

            .login-btm {
                float: left;
            }

            .login-button {
                padding-right: 0px;
                text-align: right;
                margin-bottom: 25px;
            }

            .login-text {
                text-align: left;
                padding-left: 0px;
                color: #A2A4A4;
            }

            .loginbttm {
                padding: 0px;
            }

            .row {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-2"></div>
                <div class="col-lg-6 col-md-8 login-box">
                    <div class="col-lg-12 login-key">
                        <i class="fa fa-key" aria-hidden="true"></i>
                    </div>
                    <div class="col-lg-12 login-title">IoT</div>
                    <div><span>Mostafa Dastghibi Shirazi</span></div>

                    <div class="col-lg-12 login-form">
                        <div class="col-lg-12 login-form">
                            <form action="./" method="post">
                                <div class="form-group">
                                    <label class="form-control-label">USERNAME</label>
                                    <input type="text" name="username" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">PASSWORD</label>
                                    <input type="password" name="password" class="form-control" i>
                                </div>

                                <div class="col-lg-12 loginbttm">
                                    <div class="col-lg-6 login-btm login-text">
                                        <!-- Error Message -->
                                    </div>
                                    <div class="col-lg-6 login-btm login-button">
                                        <button type="submit" class="btn btn-outline-primary">LOGIN</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2"></div>
                </div>
            </div>
    </body>

    </html>
<?php endif; ?>