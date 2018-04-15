<?php

function parseToXML($htmlStr)
{
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

    $db = Db::getConnection();

    $sql = "SELECT * FROM markers";
    $result = $db->prepare($sql);

    $result->execute();

    $result->setFetchMode(PDO::FETCH_ASSOC);

    header("Content-type: text/xml");

    echo '<markers>';

    while ($row = $result->fetch()) {
        echo '<marker ';
        echo 'name="' . parseToXML($row['username']) . '" ';
        echo 'lat="' . $row['lat'] . '" ';
        echo 'lng="' . $row['lng'] . '" ';
        echo '/>';
    }

    echo '</markers>';