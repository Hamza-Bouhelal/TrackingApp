<?php
require("connect.php");

$tid = $_GET["tid"];
$stmt = $conn->prepare("SELECT * FROM tracks WHERE tid = ?");
$stmt->bind_param("s", $tid);
$stmt->execute();
$select = $stmt->get_result();
if (!mysqli_num_rows($select)) {
    echo "error";
    return;
}


$ip = $_GET["ip"];
$network = $_GET["network"];
$version = $_GET["version"];
$city = $_GET["city"];
$region = $_GET["region"];
$country = $_GET["country"];
$postal = $_GET["postal"];
$latitude = $_GET["latitude"];
$longitude = $_GET["longitude"];
$timezone = $_GET["timezone"];
$org = $_GET["org"];
$utc_offset = $_GET["utc_offset"];
$languages = $_GET["languages"];
$date = date("Y-m-d H:i:s");
$user_agent = $_GET["user_agent"];

// Insert the data into the "entries" table
$sql = "INSERT INTO entries (tid, ip, network, version, city, region, country, postal, latitude, longitude, timezone, org, utc_offset, languages, date, user_agent)
VALUES ('$tid', '$ip', '$network', '$version', '$city', '$region', '$country', '$postal', '$latitude', '$longitude', '$timezone',  '$org', '$utc_offset', '$languages', '$date', '$user_agent')";
mysqli_query($conn, $sql);
$updateIncrementedCount = mysqli_query($conn, "UPDATE tracks SET clickCount = clickCount + 1 WHERE tid = '" . strtolower($tid) . "'");
echo "success";
mysqli_close($conn);
?>