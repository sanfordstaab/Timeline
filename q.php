<?php
// q.php
/*
 * This file is designed to be called via AJAX calls for Database Access 
 * Inputs:
 * q=<QueryNumber> - the query to select
 * f=<format> - which format to apply to the results
 * d=<data> - data for queries - generally in the form of "<name>^<value>|....|"
 */
require_once('queries.php');
$q = gep('q');
$f = gep('f');
$d = gep('d');
if (!is_numeric($q) || !array_key_exists($q, $aQueries)) {
    die("Unrecognized query index.");
}
echo formatQueryI($q, $d);
?>
