<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/trackingApp");
}
require("connect.php");
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
            width: 60%;
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
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            width: 15%;
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
    </style>
</head>

<body>
    <section class="forms-section">
        <h1 class="section-title">Pro. Tracker</h1>
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
            <input type="text" placeholder="Enter url to be spoofed" name="url" id="url">
            <button id="addBtn" class="buttonAdd">Add</button>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM tracks WHERE uid = '" . $_SESSION['user_id'] . "'");
            if (mysqli_num_rows($select) == 0) {
                ?>
                <div class="no-data-text">No data yet, enter a link to be spoofed !</div>
                <?php
            } else {
                ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>URL</th>
                        <th>URL (spoofed)</th>
                        <th>Clicks</th>
                        <th>View</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    /* iterate over results of query */
                    while ($row = mysqli_fetch_assoc($select)) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['createdAt'] ?>
                            </td>
                            <td><a href="<?php echo $row['url'] ?>"><?php echo $row['url'] ?></a></td>
                            <td><a href="http://localhost/trackingApp/spoofed.php?key=<?php echo $row['tid'] ?>">http://localhost/trackingApp/spoofed.php?key=<?php
                               echo $row['tid'] ?></a></td>
                            <td>
                                <?php echo $row['clickCount'] ?>
                            </td>
                            <td>
                                <a href="http://localhost/trackingApp/view.php?key=<?php echo $row['tid'] ?>"
                                    style="text-decoration: none;">
                                    <img src="static/view.svg" width="20" height="20" title="View"
                                        style="vertical-align: middle;">
                                    <span style="text-decoration: underline; vertical-align: middle;">View</span>
                                </a>
                            </td>
                            <td>
                                <a href="http://localhost/trackingApp/delete.php?key=<?php echo $row['tid'] ?>"
                                    style="text-decoration: none;">
                                    <img src="static/delete.svg" width="20" height="20" title="Delete"
                                        style="vertical-align: middle;">
                                    <span style="text-decoration: underline; vertical-align: middle;">Delete</span>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
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

        const addBtn = document.getElementById("addBtn");
        addBtn.addEventListener("click", function () {
            if (document.querySelector("input[type='text']").value == "") {
                alert("Please enter a URL to be spoofed");
                return;
            }

        });
        $("#addBtn").click(function (e) {
            e.preventDefault();
            if ($("#url").val() == "") {
                alert("Please enter a URL to be spoofed");
                return;
            }
            var formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "track.php?url=" + $("#url").val(),
                data: formData,
                success: function (response) {
                    if (response == "success") {
                        location.reload();
                    }
                    else {
                        alert(response);
                    }
                }
            });
        });
    </script>
</body>

</html>