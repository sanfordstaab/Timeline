<?php
// project independent php functions for DB query support.
require_once('config.php'); // defines DB_ constants for DB connection
// queries.php is assumed to already be included before this file.  
// Since this file is for project independent code, it does not reference any specific queries.
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if(!$mysqli) {
	die('Error:Failed to connect to server: Host:' . DB_HOST . ' User:' . DB_USER . ' Database:' . DB_DATABASE);
}

function gep($p) {
    if (isset($_GET[$p])) {
        return $_GET[$p];
    } else if (isset($_POST[$p])) {
        return $_POST[$p];
    } else {
        return "";
    }
}

// applies data to a query which contains [#] placeholders for the data.
// sData takes the form of value<delimeter>value....<delimeter>
// the last character of sData is the delimeter to use
function applyDataToQuery($query, $sData) {
    global $aQueries;
    if ($sData && (strstr($sData, '[') != -1)) {
        // $sData is "value|value|...|value|" (| is the chDelim in this example)
        $aData = explode(substr($sData, -1, 1), $sData);
        for ($i = 0; $i < count($aData) - 1; $i++) {
            $query = str_replace("[".$i."]", $aData[$i], $query);
        }
    }
    return $query;
}
function execQuery($sQuery) {
    // we assume mysqli interface
    global $mysqli;
    return mysqli_query($mysqli, $sQuery);
}
function standardFormatResults($results, $sQuery) {
    global $mysqli;
    $out = '';

    if ($results === true && (stripos($sQuery, 'insert ') == 0)) {
        $out = strval(mysqli_insert_id($mysqli)).'^|';  // table of one value
    } else if ((gettype($results) != "boolean")) { // we have real results
        $aTable = array();
        if(mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_assoc($results)) {
                $aRow = array();
                foreach ($row as $value) {
                    array_push($aRow, $value);
                }
                array_push($aTable, $aRow);
            }
            $out = formatTableToString($aTable);
        }
    }
    return $out;
}
$sepChars = "^|_- ,.;:~abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; // chars that won't give us problems in order of desireability
function formatTableToString($aTable) {
    global $sepChars;
    // concatonates a table of string data into a single string with delimeters chosen
    // based on the table contents.  The string ends with the row delimeter and
    // each row ends with the value delimeter.
    $concat = '';
    foreach ($aTable as $aRow) {
        foreach ($aRow as $value) {
            $concat .= $value;
        }
    }
    // pick delimeters that are not in the data
    $rowDelim = '';
    $valueDelim = '';
    for ($iSep = 0; $iSep < strlen($sepChars); $iSep++) {
        $ch = substr($sepChars, $iSep, 1);
        if ($valueDelim == '') {
            if (strpos($concat, $ch) === FALSE) {
                $valueDelim = $ch;
            }
        } else {
            if (strpos($concat, $ch) === FALSE) {
                $rowDelim = $ch;
                break;  
            }
        }
    }

    $concat = '';
    foreach ($aTable as $aRow) {
        foreach ($aRow as $value) {
            $concat .= $value . $valueDelim;
        }
        $concat .= $rowDelim;
    }
    return $concat;
}
function formatQuery($sFormattedQuery, // DB query to make with [#] for parameter substitution.
                     $queryData  // parameters in standard (see standardFormatResults) used to fill in values for a query.
                     ) {
    $out = '';
    $sQuery = applyDataToQuery($sFormattedQuery, $queryData);
    $result = execQuery($sQuery);
    // $result is boolean for inserts or other no-output queries while selects are set to a result object.
    if ($result) {
        $out = standardFormatResults($result, $sQuery);
        if (gettype($result) != "boolean") {
            mysqli_free_result($result);
        }
    } else {
        $out = "Error: Query failed.";
        error_log("Query failed: ".$sQuery, 0);   // a way to see the queries being done for debuging
    }
    if (gep('log')) {
        error_log($sQuery." = ".$out."\n", 3, dirname($_SERVER['SCRIPT_FILENAME']).'\qlog.txt');
    }
    return $out;
}
// shortcut version only take array indexes
// $data is 'v1^v2^...vN|v1^v2^...vN|...|' (| is the chDelim in this example)
function formatQueryI($queryIndex, $sData) {
    global $aQueries;   // form queries.php - project specific
    if (!array_key_exists($queryIndex, $aQueries)) {
        return 'Error: query index '.$queryIndex.' does not exist.'; // 'Error:' so ajax.failCheck will recognize the error.
    }
    $cParamsNeeded = 0;
    $sDataDesc = $aQueries[$queryIndex][Q_DATADESC];
    if ($sDataDesc != '') {
        $cParamsNeeded = count(explode(',', $sDataDesc));
    }
    $cParamsIn = 0;
    if ($sData != '') {
        $delim = substr($sData, -1);
        $cParamsIn = count(explode($delim, $sData)) - 1;
    }
    if ($cParamsIn != $cParamsNeeded) {
        return "Error: ".$cParamsIn." parameters given. (".$sData.") ".$cParamsNeeded." parameters were needed.";
    }
    $sQuery = $aQueries[$queryIndex][Q_QUERY];
    $out = formatQuery($sQuery, $sData);
    return $out;
}
?>
