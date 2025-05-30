<?php

$query = "SELECT a.nid, a.uid, b.username, a.date, a.title, a.message, b.icon
FROM tsms_news a, tsms_users b
WHERE b.uid = a.uid
ORDER BY a.date DESC
LIMIT 1";

$mainsite_db_host = "localhost";
$mainsite_db_username = 'user';
$mainsite_db_tablename = 'dbname';
$mainsite_db_password = 'password';

$mainsite_connection = mysqli_connect($mainsite_db_host,$mainsite_db_username,$mainsite_db_password,$mainsite_db_tablename);
if ($mainsite_connection->connect_errno) //toss error
{
echo "Failed to connect to MySQL: (" . $mainsite_connection->connect_errno . ") " . $mainsite_connection->connect_error;
}

if (mysqli_multi_query($mainsite_connection,$query))
{
do
{
// Store first result set
if ($result = mysqli_store_result($mainsite_connection))
{
    // Fetch one and one row
    while ($row = mysqli_fetch_row($result))
    {
        $update = str_replace("&amp;", "&", htmlspecialchars("https://mfgg.net/index.php?act=main&param=02&id=")).$row[0];
        $user = str_replace("&amp;", "&", htmlspecialchars("https://mfgg.net/index.php?act=user&param=01&uid=")).$row[1];
        $username = $row[2];
        $title = $row[4];
        $message = str_replace("&#39;", "'", strip_tags( str_replace("<br>", "\n", substr($row[5], 0, strpos($row[5], "<b>Recent Additions</b>") ) ) ) );
        $img_start = strpos($row[5], "https://www.mfgg.net/thumbnail/");
        $thumbnail = substr($row[5], $img_start, strpos($row[5], "' border='0' alt='Thumbnail'>")-$img_start );
        $icon = $row[6];
    }
    // Free result set
    mysqli_free_result($result);
}
}
while (mysqli_next_result($mainsite_connection));
}

mysqli_close($mainsite_connection);

// The webhook URL
$url = "https://discordapp.com/api/webhooks/606549878394322944/cJJvQKa04bQLEB-q_0yDhro6rEih9riZDF7yCtliFdKMfeskVY73rQtUNIhHKP5PXSRd";

$hookObject = json_encode([
    "content" => "",
    "username" => $username,
    "avatar_url" => $icon,
    "tts" => false,
    "embeds" => [
        [
            "title" => $title,
            "type" => "rich",
            "description" => preg_replace('/\n(\s*\n){2,}/', "\n\n", $message)."**[[Read More]](".$update.")**",
            "url" => $update,
            "color" => hexdec( "FF5B5B" ),
            "author" => [
                "name" => $username,
                "url" => $user,
                "icon_url" => $icon
            ],
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


//echo $hookObject;

$ch = curl_init();

curl_setopt_array( $ch, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Length" => strlen( $hookObject )
    ]
]);

$response = curl_exec( $ch );
curl_close( $ch );

header("Location: https://mfgg.net/admin.php?act=manage&param=08"); 
?>