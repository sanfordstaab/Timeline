<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript">
// This file should be included within the <head> area of the page.
<?php
// This file generates the queries constants in javascript form. It also contains all Js and Php functions necessary to support queries
require_once('queries.php');
echo 'var ';
foreach ($aQueries as $k => $v) echo ($k == 0 ? '' : ",\n") . $v[Q_NAME] . '=' . $k; 
echo ";\n";
?>
function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}
function isArray(testObject) {	 
  return testObject && 
         (testObject.propertyIsEnumerable && !testObject.propertyIsEnumerable('length')) && 
         typeof testObject === 'object' && 
         typeof testObject.length === 'number';
}
var logQueries = 0;    
function doQuery(iQuery, aData, fnContinue, continueInfo, sCallerInfo) {
    var sURL = 'q.php?q=' + iQuery + '&d=' + encodeURIComponent(stdFormatRowToString(aData));
    if (logQueries) {
        sURL += '&log=1';
    }
    ajax.post(sURL, fnContinue, continueInfo, '<a href="' + sURL + '">' + sCallerInfo + '</a>');
}
function stdFormatTableToString(aaIn) {  // same as php function in qworker.php:formatTableToString
    // concatonates a table (array of arrays) of string data into a single string with delimeters chosen
    // based on the table contents.  The string ends with the row delimeter and
    // each row ends with the value delimeter.
    var concat = '';
    var sepChars = "<?php echo $sepChars ?>"; // from queries.php
    for (var iRow = 0; iRow < aaIn.length; iRow++) {
        concat += aaIn[iRow].join("");
    }
    // pick delimeters that are not in the data
    var rowDelim = '';
    var valueDelim = '';
    for (iSep = 0; iSep < sepChars.length; iSep++) {
        var ch = sepChars.charAt(iSep);
        if (valueDelim == '') {
            if (concat.indexOf(ch) == -1) {
                valueDelim = ch;
            }
        } else {
            if (concat.indexOf(ch) == -1) {
                rowDelim = ch;
                break;  
            }
        }
    }
    concat = '';
    for (var iRow = 0; iRow < aaIn.length; iRow++) {
        concat += aaIn[iRow].join(valueDelim) + valueDelim + rowDelim;
    }
    return concat;
}
function stdFormatRowToString(aIn) {
    if (!aIn.length) {
        return '';
    }             
    var sepChars = "<?php echo $sepChars ?>"; // from queries.php
    var concat = aIn.join("");
    var valueDelim = '';
    for (iSep = 0; iSep < sepChars.length; iSep++) {
        var ch = sepChars.charAt(iSep);
        if (valueDelim == '') {
            if (concat.indexOf(ch) == -1) {
                valueDelim = ch;
                break;  
            }
        }
    }
    return aIn.join(valueDelim) + valueDelim;
}
function stdFormatToTable(sIn) {
    // sIn contains a string that ends with a row delimeter char and each row ends with a value delimeter char.
    // aOut is a two-dimensional table of string values derived from sIn
    var chRowDelim = sIn.substr(sIn.length - 1);
    var chValueDelim = sIn.substr(sIn.length - 2, 1);
    var aOut = sIn.substring(0, sIn.length - 1).split(chRowDelim);
    for (var i = 0; i < aOut.length; i++) {
        aOut[i] = aOut[i].substring(0, aOut[i].length - 1).split(chValueDelim);
    }
    return aOut;
}
function stdFormatToRow(sIn) { // sIn is a string ending in a delimeter char
    var chDelim = sIn.substr(sIn.length - 1);
    return sIn.substring(0, sIn.length - 1).split(chDelim);
}
function stdFormatXform(sIn, sNewRowDelim, sNewValueDelim) {
    var specialChars = "!@#$^&%*()+=-[]\/{}|:<>?,.";
    var chRowDelim = sIn.substr(sIn.length - 1);
    if (specialChars.indexOf(chRowDelim) != -1) {
        chRowDelim = '\\' + chRowDelim;
    }
    var chValueDelim = sIn.substr(sIn.length - 2, 1);
    if (specialChars.indexOf(chValueDelim) != -1) {
        chValueDelim = '\\' + chValueDelim;
    }
    return sIn.substr(0, sIn.length - 2).replace(new RegExp(chRowDelim, "g"), sNewRowDelim).replace(new RegExp(chValueDelim, "g"), sNewValueDelim);
}
function stdFormatToHTML(sIn) {
    return stdFormatXform(sIn, '<br>', ', ');
}
function stdFormatGetValue(sIn, iRow, iColumn) {
    if (sIn) {
        var chRowDelim = sIn.substr(sIn.length - 1);
        var chValueDelim = sIn.substr(sIn.length - 2, 1);
        return sIn.split(chRowDelim)[iRow].split(chValueDelim)[iColumn];
    } else {
        return '';
    }
}
function applyToInnerHTML(fSuccess, responseText, targetId) {
    if (isArray(targetId)) {
        for (var i = 0; i < targetId.length; i++) {
            applyToInnerHTML(fSuccess, responseText, targetId[i]);
        }
    } else {
        applyEToInnerHTML(fSuccess, responseText, document.getElementById(targetId));
    }
}
function applyEToInnerHTML(fSuccess, responseText, targetElement) {
    targetElement.innerHTML = stdFormatGetValue(responseText, 0, 0);
}
function applyToValue(fSuccess, responseText, targetId) {
    if (isArray(targetId)) {
        for (var i = 0; i < targetId.length; i++) {
            applyToValue(fSuccess, responseText, targetId[i]);
        }
    } else {
        applyToValue(fSuccess, responseText, document.getElementById(targetId));
    }
}
function applyEToValue(fSuccess, responseText, targetElement) {
    targetElement.value = stdFormatGetValue(responseText, 0, 0);
}
function applyToSelectItemByValue(fSuccess, responseText, selectId) {
    // sets the mono-select control to select the item who's value equals the first result value.
    if (isArray(selectId)) {
        for (var i = 0; i < selectId.length; i++) {
            applyToSelectItemByValue(fSuccess, responseText, selectId[i]);
        }
    } else {
        eS = document.getElementById(selectId);
        applyEToSelectItemByValue(fSuccess, responseText, eS);
    }
}
function applyEToSelectItemByValue(fSuccess, responseText, eSel) {
    // sets the mono-select control to select the item who's value equals the first result value.
    for (var i = 0; i < eSel.options.length; i++) {
        if (eSel.options[i].value == stdFormatGetValue(responseText, 0, 0)) {
            eSel.options[i].selected = true;
        }
    }
}
function applyToSelect(fSuccess, responseText, targetId) {
    if (isArray(targetId)) {
        for (var i = 0; i < targetId.length; i++) {
            applyToSelect(fSuccess, responseText, targetId[i]);
        }
    } else {
        applyEToSelect(fSuccess, responseText, document.getElementById(targetId));
    }
}
function applyEToSelect(fSuccess, responseText, eSel) {
    eSel.options.length = 0;
    if (fSuccess) {
        var aOpts = stdFormatToTable(responseText);
        for (var i = 0; i < aOpts.length; i++) {
            var opt = aOpts[i];
            var newOp = document.createElement("option");
            newOp.value = opt[0];
            newOp.innerHTML = opt[1];
            eSel.options.add(newOp);
        }
    }
}
function applyToSelectWithNewOption(fSuccess, responseText, targetId) {
    if (isArray(targetId)) {
        for (var i = 0; i < targetId.length; i++) {
            applyToSelectWithNewOption(fSuccess, responseText, targetId[i]);
        }
    } else {
        applyEToSelectWithNewOption(fSuccess, responseText, document.getElementById(targetId));
    }
}
function applyEToSelectWithNewOption(fSuccess, responseText, eTarget) {
    applyEToSelect(fSuccess, responseText, eTarget);
    if (fSuccess) {
         var newOp = document.createElement("option");
         newOp.value = -2;
         newOp.innerHTML = 'New-->';
         eTarget.options.add(newOp);
    }
}
function applyToSelectWithAnyOption(fSuccess, responseText, targetId) {
    if (isArray(targetId)) {
        for (var i = 0; i < targetId.length; i++) {
            applyEToSelectWithAnyOption(fSuccess, responseText, document.getElementById(targetId[i]));
        }
    } else {
        applyEToSelectWithAnyOption(fSuccess, responseText, document.getElementById(targetId));
    }
}
function applyEToSelectWithAnyOption(fSuccess, responseText, eTarget) {
    applyEToSelect(fSuccess, responseText, eTarget);
    if (fSuccess) {
         var newOp = document.createElement("option");
         newOp.value = -2;
         newOp.innerHTML = '...Any...';
         eTarget.options.add(newOp);
    }
}
function applyTextToInnerHTML(fSuccess, resultText, aContext) {
     document.getElementById(aContext[0]).innerHTML = aContext[1];
}
</script>
