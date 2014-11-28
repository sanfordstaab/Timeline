<html>
<head>
<?php
require_once('jsQueryConstants.php'); ?>
  <script type="text/javascript">
<?php
echo "var aQueryText = [\n";
for ($i = 0; $i < count($aQueries); $i++) {
    echo "'".$aQueries[$i][Q_QUERY]."'";
    if ($i < count($aQueries) - 1) echo ",";
    echo "\n";
}
echo "];\n";
echo "var aQueryDataDesc = [\n";
for ($i = 0; $i < count($aQueries); $i++) {
    echo "'".$aQueries[$i][Q_DATADESC]."'";
    if ($i < count($aQueries) - 1) echo ",";
    echo "\n";
}
echo "];\n";
?>
ajax.errorHandler = function(sCallerInfo, sCalleeError) {}; // disaple default alert popup.
function submitQuery() {
    var eQ = document.getElementById("q");
    var q = eQ.value;
    var eD = document.getElementById("data");
    var aData = stdFormatToRow(eD.value);

    eA = document.getElementById("url");
    eA.href = "q.php?q=" + q + "&d=" + eD.value;
    eA.innerHTML = eA.href;

    eA = document.getElementById("jsCall");
    eA.innerHTML = "doQuery{" + eQ.options[q].innerHTML + ", " + "\"" + aData + "\", [fnContinue], [targetInfo]);";

    eA = document.getElementById("phpCall");
    eA.innerHTML = "formatQueryI{" + eQ.options[q].innerHTML + ", " + "\"" + aData + "\");";

    sQuery = document.getElementById("queryText").innerHTML;
    for (var iValue = 0; iValue < aData.length; iValue++) {
        sQuery = sQuery.replace(new RegExp('\\[' + iValue + '\\]', 'g'), aData[iValue])
    }
    document.getElementById('formattedQuery').innerHTML = sQuery;
    doQuery(q, aData, formatOutput, 'result', 'qtest.php:submitQuery()')
}
function formatOutput(fSuccess, resultText, idTarget) {
    var eS = document.getElementById(idTarget);
    if (resultText.indexOf("Error:") == 0) {
        eS.innerHTML = resultText;
    } else if (resultText){
        eS.innerHTML = stdFormatToHTML(resultText);
    } else {
        eS.innerHTML = 'No results returned.';
    }
}
function htmlEscape(str) {
    return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
}
function getQueryText(iQuery) {
    document.getElementById('queryText').innerHTML = aQueryText[iQuery];
    document.getElementById('dataDesc').innerHTML = aQueryDataDesc[iQuery];
    document.getElementById('data').value = '';
    checkData();
}
function checkData() {
    var desc = document.getElementById('dataDesc').innerHTML;
    var cParamsNeeded = 0;
    if (desc.length) {
        cParamsNeeded = desc.split(',').length;
    }
    var data = document.getElementById('data').value;
    var rowIn = stdFormatToRow(data);
    var cParamsGiven = rowIn.length;
    var fOk = (cParamsNeeded == cParamsGiven);
    document.getElementById('submit').disabled = !fOk;
    var eInterp = document.getElementById('dataInterp');
    eInterp.innerHTML = cParamsGiven + ' parameters:[' + rowIn.join('],[') + ']';
    eInterp.style.cssText = '{ color:' + (fOk ? 'green' : 'red') + '; }';
}
function tableKeyDown(eTable, event) {
    // this routes key events to the proper controls so you don't need to change focus manually to
    // work this page by the keyboard only.
    if (event.keyCode == 40 || event.keyCode == 38) {
        document.getElementById('q').focus();
    } else if (event.keyCode == 13) {
        document.getElementById('submit').focus();
    } else {
        document.getElementById('data').focus();
    }
    return false;
}
function PageLoad() {
    document.getElementById("q").selectedIndex = 0;
    getQueryText(0);
}
  </script>
  <style type="text/css">
  body { background-color: #DDFFAA; font-family: Arial; font-size: 14pt; }
  .ac { text-align:center; }
  .ar { text-align:right; }
  .blue { color:blue; }
  .green { color:green; }
  .red { color:red; }
  .purple { color:purple; }
  .brown { color:brown; }
  .white { background-color:white; }
  .monospace { font-family:'Courier New'; font-size:9pt; }
  .frame { background-color:#66FF99; }
  </style>
</head>
<body onload="PageLoad();">
<h1 class="ac">Ajax Query Tester</h1>
<table border="0" align="center" border="0" cellspacing="1" class="frame" onkeydown="tableKeyDown(this, event);">
  <!-------------------------------------->
  <tr>
    <td class="ar purple">Query Text:</td>
    <td>
      <select id="q" onchange="getQueryText(this.value);" class="purple" size="15">
  <?php
    for ($i = 0; $i < count($aQueries); $i++) {
        echo "<option value=\"".$i."\">".$aQueries[$i][Q_NAME]."</option>\n";
    }
  ?>
      </select>
    </td>
    <td><textarea id="queryText" cols="57" rows="15" class="purple"></textarea></td>
  </tr>
  <tr>
    <td class="ar blue">Data needed:</td>
    <td colspan="2" id="dataDesc" class="blue"></td>
  </tr>
  <tr>
    <td class="ar red">Input Data:</td>
    <td colspan="2"><textarea cols="50" title="Enter data with delimeters like: foo|bar|3|7| - always end the input with the delimeter." rows=1 id="data" class="red" onkeyup="checkData();"></textarea>
    Data Interpretation:<span id="dataInterp"></span></td>
  </tr>
  <tr>
    <td class="ar">Formatted Query:</td>
    <td colspan="2"><textarea cols="126" rows="3" title"This is the query after substituting the input data into the Query Text." class="monospace white" id="formattedQuery"></textarea></td>
  </tr>
  <tr>
    <td class="ar">Ajax URL:</td>
    <td colspan="2"><a title="Click this to run the same query that an AJAX call would run." id="url"></a></td>
  </tr>
  <tr>
    <td class="ar">Javascript Call (AJAX):</td>
    <td colspan="2"><textarea cols="126" rows="3" class="monospace white" id="jsCall"></textarea></td>
  </tr>
  <tr>
    <td class="ar">PHP Call:</td>
    <td colspan="2"><textarea cols="126" rows="3" class="monospace" id="phpCall"></textarea></td>
  </tr>
  <tr>
    <td class="ac" colspan="3"><input type="submit" id="submit" value="Try Query" onclick="submitQuery();"></input></td>
  </tr>
  <tr>
    <td class="ar">HTML Formatted Results:</td>
    <td colspan="2"><span id="result"></span></td>
  </tr>
</table>
</body>
</html>
