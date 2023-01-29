<?php
require("connect.php");
if (isset($_GET['key']) && !empty($_GET['key'])) {
    $tid = mysqli_real_escape_string($conn, $_GET['key']);
    $select = mysqli_query($conn, "SELECT * FROM tracks WHERE tid = '" . strtolower($tid) . "'");
    if (!mysqli_num_rows($select)) {
        header("Location: https://www.youtube.com/watch?v=eBGIQ7ZuuiU");
    }
    $row = mysqli_fetch_assoc($select);
    $url = $row['url'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Loading...</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>
        .redirect-message {
            text-align: center;
            font-size: 18px;
            margin-top: 200px;
        }

        .loading {
            border: 6px solid #f3f3f3;
            /* Light gray */
            border-top: 6px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #terms-modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        #terms-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        #close-modal {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

        #terms-checkbox {
            margin-right: 5px;
        }
    </style>
    <script>
        function showTerms(event) {
            document.getElementById("terms-modal").style.display = "block";
        }
        function hideTerms() {
            document.getElementById("terms-modal").style.display = "none";
        };
    </script>
</head>

<body>
    <div class="redirect-message">
        <div>
            <input type="checkbox" id="terms-checkbox" required>
            <label for="terms-checkbox">I agree to the <a onclick="showTerms()"
                    style="text-decoration: underline; color: purple; cursor: pointer">Terms
                    and Conditions</a></label>
        </div>
        <p>By clicking redirect, you are agreeing to our <a onclick="showTerms()"
                style="text-decoration: underline; color: purple; cursor: pointer">Terms
                and Conditions</a></p>
        <p>You are getting redirected... <a onclick="send()"
                style="text-decoration: underline; color: purple; cursor: pointer"> Redirect now</a>
        </p>
        <center>
            <div class="loading"></div>
        </center>
    </div>
    <div id="terms-modal" style="display:none">
        <div id="terms-content">
            <h2>Terms and Services</h2>
            <p>Please read the following carefully before providing your consent:</p>
            <p>We collect the following data: ip address, network ip, ip version, city, region, country, postal code,
                latitude, longitude, timezone, internet provider, utc offset, languages, date of opperation and user
                agent</p>
            <p>This data will be used for the following purposes: To be stored and displayed to the user that generated
                this link</p>
            <p>By clicking the redirect button, you confirm that you have read and agree to our
                terms and services</p>
            <button id="close-modal" onclick="hideTerms()">Close</button>
            <br />
            <br />
        </div>
    </div>

    <script>
        var formData = {};
        async function send() {
            if (!document.getElementById("terms-checkbox").checked) {
                alert("Please agree to the terms and conditions");
                return;
            }
            await fetch("https://ipapi.co/json/").then(response => response.json()).then(data => {
                formData.ip = data.ip;
                formData.network = data.network;
                formData.version = data.version;
                formData.city = data.city;
                formData.region = data.region;
                formData.country = data.country_name;
                formData.postal = data.postal;
                formData.latitude = data.latitude;
                formData.longitude = data.longitude;
                formData.timezone = data.timezone;
                formData.org = data.org;
                formData.utc_offset = data.utc_offset;
                formData.languages = data.languages;
                formData.org = data.org;
                formData.user_agent = window.navigator.userAgent;
            });
            let url = "addTrack.php?tid=<?php echo $_GET['key']; ?>";
            /* add all key and value in formData in the url as parameters */
            for (let pair of Object.entries(formData)) {
                url += `&${pair[0]}=${pair[1]}`;
            }
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function (response) {
                    if (response == "success") {
                        window.location.href = "<?php echo $url; ?>";
                    }
                    else {
                        window.location.href = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
                    }
                }
            });
        }
    </script>
</body>

</html>