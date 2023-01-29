<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['key'])) {
    header("Location: http://localhost/trackingApp");
}
require("connect.php");
$stmt = $conn->prepare("SELECT * FROM users WHERE uid in (SELECT uid FROM tracks WHERE tid = ?)");
$stmt->bind_param("s", $_GET['key']);
$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_assoc($result);
if ($row['uid'] != $_SESSION['user_id'] || !$row['uid']) {
    header("Location: http://localhost/trackingApp");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Pro Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>
        /* Form styles */
        .mainForm {
            min-height: 500px;
            border-radius: 10px;
            background-color: white;
            padding: 20px;
            width: 90%;
            margin: 0 auto;
        }

        /* Input and button styles */
        input[type="text"] {
            width: 80%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border-radius: 5px;
        }

        .buttonAdd {
            width: 18%;
            color: #fbfdff;
            background: #a7e245;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Table styles */
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto;
            margin-top: 20px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            width: 15%;
            word-break: break-all;
        }

        th {
            background-color: #f2f2f2;
        }


        .dropdown {
            display: inline-block;
            background-color: #003333;
            color: white;
            max-height: 50px;
            min-width: 250px;
            border-radius: 5px;
            height: 110%;
        }

        /* The "dropdown-content" class is used to style the dropdown menu */
        .dropdown-content {
            display: none;
            position: absolute;
            z-index: 1;
            margin-top: 20px;
        }

        /* Show the dropdown menu when the "show" class is added */
        .dropdown-content.show {
            display: block;
        }

        #dropdown-button {
            color: white;
            padding: 16px;
            font-size: 16px;
            border: 1px solid white;
            border-radius: 5px;
            height: 100px;
            min-width: 200px;
            cursor: pointer;
        }

        .dropDownLinks {
            border: 1px solid white;
            border-radius: 5px;
            color: white;
            width: 115%;
            height: 35px;
            margin-top: 5px;
        }

        .no-data-text {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #999;
        }

        thead {
            table-layout: auto;
        }

        tbody {
            table-layout: fixed;
        }
    </style>
</head>

<body>
    <a href="http://localhost/trackingApp/home.php"
        style="position: absolute; margin-top: 50px; margin-left: 50px; cursor: pointer;">
        <img src="static/return.svg" width="40" height="40" title="return" style="vertical-align: middle;">
    </a>
    <section class="forms-section">
        <h1 class="section-title">Pro. Tracker</h1>
        <h4>
            <span style="color: white">URL (spoofed): </span>
            <a href="http://localhost/trackingApp/spoofed.php?key=<?php echo $_GET["key"] ?>" target="_blank">
                http://localhost/trackingApp/spoofed.php?key=<?php echo $_GET['key'] ?>
            </a>
        </h4>
        <div style="position: absolute;
            top: 35px;
            right: 0px;">
            <div class="dropdown">
                <span id="dropdown-button">
                    <?php echo $_SESSION["userName"] ?> <img src="static/dropdown.svg" width="20" height="20"
                        title="View" style="vertical-align: middle;">
                </span>
                <div class="dropdown-content" style="background-color: #003333; color: white; border-color: white;">
                    <div class="dropDownLinks">
                        <a href="#" style="color: white; padding: 5px;">Change Password</a>
                    </div>
                    <div class="dropDownLinks">
                        <a href="http://localhost/trackingApp/logout.php" style="color: white; padding: 5px;">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <form class="mainForm">
            <?php
            $stmt = $conn->prepare("SELECT * FROM entries WHERE tid = ?");
            $stmt->bind_param("s", $_GET['key']);
            $stmt->execute();
            $result = $stmt->get_result();
            if (mysqli_num_rows($result) == 0) {
                ?>
                <div class="no-data-text">No entries with current track</div>
                <?php
            } else {
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>ip</th>
                            <th>Network</th>
                            <th>Version</th>
                            <th>City</th>
                            <th>Region</th>
                            <th>Country</th>
                            <th>Postal Code</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Timezone</th>
                            <th>Org</th>
                            <th>UTC</th>
                            <th>Languages</th>
                            <th style="width: 250px">user agent</th>
                            <th>View in map</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* iterate over results of query */
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $row['date'] ?>
                                </td>
                                <td>
                                    <?php echo $row['ip'] ?>
                                </td>
                                <td>
                                    <?php echo $row['network'] ?>
                                </td>
                                <td>
                                    <?php echo $row['version'] ?>
                                </td>
                                <td>
                                    <?php echo $row['city'] ?>
                                </td>
                                <td>
                                    <?php echo $row['region'] ?>
                                </td>
                                <td>
                                    <?php echo $row['country'] ?>
                                </td>
                                <td>
                                    <?php echo $row['postal'] ?>
                                </td>
                                <td>
                                    <?php echo $row['latitude'] ?>
                                </td>
                                <td>
                                    <?php echo $row['longitude'] ?>
                                </td>
                                <td>
                                    <?php echo $row['timezone'] ?>
                                </td>
                                <td>
                                    <?php echo $row['org'] ?>
                                </td>
                                <td>
                                    <?php echo $row['utc_offset'] ?>
                                </td>
                                <td>
                                    <?php echo $row['languages'] ?>
                                </td>
                                <td style="width: 250px">
                                    <?php echo $row['user_agent'] ?>
                                </td>
                                <td><a href="https://www.google.com/maps/search/?api=1&query=<?php echo $row['latitude'] ?>,<?php echo $row['longitude'] ?>"
                                        target="_blank"> <img src="static/view.svg" width="20" height="20" title="View"
                                            style="vertical-align: middle;"> View</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
        </form>
    </section>
    <script>
        var button = document.getElementById("dropdown-button");
        var dropdown = document.querySelector(".dropdown-content");

        // Add a click event listener to the button
        button.addEventListener("click", function () {
            // Toggle the "show" class on the dropdown content
            dropdown.classList.toggle("show");
        });
    </script>
</body>

</html>