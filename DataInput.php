<!DOCTYPE html>
<head>
<title>Timeline Data Entry Page</title>
<?php
require_once('jsQueryConstants.php');
?>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCazcIg7op9I7-h9X4O8hiI_hSESMUy3JM&sensor=false&libraries=drawing"></script>
  <script type="text/javascript">
logQueries = 1;
var g = {
    ids: {
    }
};
function eventsFireOnChange(element) {
    if ("fireEvent" in element)
        element.fireEvent("onchange");
    else
    {
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("change", false, true);
        element.dispatchEvent(evt);
    }
} 
// ------------------------------------------------ singleMapViewer Class --------------------------------------------------
function setMapOutput(s) {
    document.getElementById('whereLocation').innerHTML = s;
}
/* class */function singleItemMapViewer(idHost, fnSetOutput) {
    this.aaaClassName = 'singleItemMapViewer';
    this.eHost = document.getElementById(idHost);
    this.fnSetOutput = fnSetOutput;
    this.lastMarker = null;
    this.circleOptions = {    
        fillColor: '#00ff00',    
        fillOpacity: 0.1,    
        strokeWeight: 1,    
        clickable: false,    
        zIndex: 1,    
        editable: false  
    };
    this.polygonOptions = {
        fillColor: '#00ff00',    
        fillOpacity: 0.1,    
        strokeWeight: 1,    
        clickable: false,    
        zIndex: 1,    
        editable: false
    };
    this.rectangleOptions = {
        fillColor: '#00ff00',    
        fillOpacity: 0.1,    
        strokeWeight: 1,    
        clickable: false,    
        zIndex: 1,    
        editable: false
    };
    this.deleteLastMarker = function() {
        if (this.lastMarker) {
            this.lastMarker.setMap(null);
        }
    };
    this.setCircle = function(centerLat, centerLng, radius) {
        this.deleteLastMarker();
        var cOpts = this.circleOptions;
        cOpts.center = new google.maps.LatLng(centerLat, centerLng, true);
        cOpts.radius = radius;
        cOpts.map = this.map;
        this.drawingManager.setDrawingMode(null);
        this.lastMarker = new google.maps.Circle(cOpts);
        this.map.panToBounds(this.lastMarker.getBounds());
        if (this.fnSetOutput) {
            this.fnSetOutput("Circle:[" + centerLat + ',' + centerLng + '] r=' + radius);
        }
    };
    this.setPolygon = function(aLatLngs, bounds) {
        this.deleteLastMarker();
        var pOpts = this.polygonOptions;
        pOpts.paths = aLatLngs;
        pOpts.map = this.map;
        this.drawingManager.setDrawingMode(null);
        this.lastMarker = new google.maps.Polygon(pOpts);
        this.map.panToBounds(bounds);
        if (this.fnSetOutput) {
            var sOut = 'Polygon:' + aLatLngs.length + ' ';
            for (var i = 0; i < aLatLngs.length; i++) {
                sOut += i + '=[' + aLatLngs[i].lat() + ',' + aLatLngs[i].lng() + '] ';
            }
            that.fnSetOutput(sOut);
        }
    };
    this.map = new google.maps.Map(this.eHost, {          
        center: new google.maps.LatLng(31.770207631866714, 34.79644775390625),  // center on Jerusalem
        zoom: 5,          
        mapTypeId: google.maps.MapTypeId.HYBRID,
        scaleControl: false,
        zoomControl: false,
        streetViewControl: false,
        mapTypeControl: false
    });
    this.drawingManager = new google.maps.drawing.DrawingManager({  
       drawingMode: google.maps.drawing.OverlayType.MARKER,  
       drawingControl: true,  
       drawingMode: null,
       drawingControlOptions: {    
          position: google.maps.ControlPosition.TOP_RIGHT,    
          drawingModes: [      
             google.maps.drawing.OverlayType.CIRCLE,      
             google.maps.drawing.OverlayType.POLYGON,      
             google.maps.drawing.OverlayType.RECTANGLE      
             ]  
       },  
       circleOptions:this.circleOptions,
       polygonOptions:this.polygonOptions,
       rectangleOptions:this.rectangleOptions
    });
    // Event handlers - note that we attach a singleItemMapViewerInstance field to the object that will be the this
    // when this function is called to get back to the instance of this object
    this.onCircleComplete = function(circle) {
        that = this.singleItemMapViewerInstance;
        that.deleteLastMarker();
        that.lastMarker = circle;
        if (that.fnSetOutput) {
            that.fnSetOutput("Circle:[" + circle.getCenter().lat() + ',' + circle.getCenter().lng() + '] r=' + circle.getRadius());
        }
    };
    this.onPolygonComplete = function(polygon) {
        that = this.singleItemMapViewerInstance;
        var path = polygon.getPaths().getArray()[0];
        that.deleteLastMarker();
        that.lastMarker = polygon;
        if (that.fnSetOutput) {
            var sOut = 'Polygon:' + path.getLength() + ' ';
            for (var i = 0; i < path.getLength(); i++) {
                sOut += i + '=[' + path.b[i].lat() + ',' + path.b[i].lng() + '] ';
            }
            that.fnSetOutput(sOut);
        }
    };
    this.onRectComplete = function(rect) {
        that = this.singleItemMapViewerInstance;
        var sOut = 'Polygon:4 ';
        var bounds = rect.getBounds();
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();
        sOut += '0=[' + ne.lat() + ',' + ne.lng() + '] ';
        sOut += '1=[' + sw.lat() + ',' + ne.lng() + '] ';
        sOut += '2=[' + sw.lat() + ',' + sw.lng() + '] ';
        sOut += '3=[' + ne.lat() + ',' + sw.lng() + '] ';
        that.deleteLastMarker();
        that.lastMarker = rect ;
        if (that.fnSetOutput) {
            that.fnSetOutput(sOut);
        }
    };
    this.onMouseDown = function(mouseEvent) {
        that = this.singleItemMapViewerInstance;
        if (that.fnSetOutput) {
            that.fnSetOutput('[' + mouseEvent.latLng.lat() + ',' + mouseEvent.latLng.lng() + ']');
        }
    };
    // End of Event Handlers
    this.drawingManager.singleItemMapViewerInstance = this; // used to connect to this instance from within the handler
    google.maps.event.addListener(this.drawingManager, 'circlecomplete', this.onCircleComplete);
    google.maps.event.addListener(this.drawingManager, 'polygoncomplete', this.onPolygonComplete);
    google.maps.event.addListener(this.drawingManager, 'rectanglecomplete', this.onRectComplete);
    this.map.singleItemMapViewerInstance = this; // used to connect to this instance from within the handler
    google.maps.event.addListener(this.map, 'rightclick', this.onMouseDown);
    this.drawingManager.setMap(this.map);
}; // singleItemMapViewer class
// ------------------------------------------------ What Functions --------------------------------------------------
function whatCreateType() {
    var typeText = ge('whatType').value.trim().toLowerCase();
    if (typeText.length) {
        doQuery(Q_SEL_WHATTYPE_COUNT_BY_TYPE, [typeText], whatCreateType2, typeText, 'whatCreateType');
    }
}
function whatCreateType2(fSuccess, resultText, typeText) {
    if (resultText == '0') {
        // ok to add the new type
        doQuery(Q_INS_WHATTYPE, [typeText], whatCreateType3, null, 'whatCreateType2');
    }
}
function whatCreateType3(fSuccess, resultText, vNull) {
    whatRefreshWhatTypeLists();
}
function whatRefreshWhatTypeLists() {
    // TODO
}
// ------------------------------------------------ Where Functions --------------------------------------------------
function whereCreate() {
    var text = ge('whereEditText').value;
    // see if a where with these keys already exists
    doQuery(Q_SEL_WHERE_BY_KEYS, [text], whereCreate2, null, 'whereCreate')
}
function whereCreate2(fSucess, resultText, vNull) {
    var existingId = stdFormatGetValue(resultText, 0, 0);
    var fExists = (existingId != '');
    if (fExists) {
        ge('whereEditId').value = existingId;
        whereUpdate();
    } else {
        var text = ge('whereEditText').value;
        var lat = ge('whereEditLat').value;
        var longitude = ge('whereEditLong').value;
        var sResult = "";
        if (chapter != Math.floor(Number(chapter))) {
            sResult = "Book chapter must be a number.";
        } else if (chapter == "") {
            sResult = "You must specify a chapter number.";
        } else if (verse != Math.floor(Number(verse))) {
            sResult = "Book verse must be a number.";
        } else if (verse == "") {
            sResult = "You must specify a verse number.";
        } 
        if (sResult) {
            ge("whereEditResult").innerHTML = sResult;
        } else {
            doQuery(Q_INS_BIBLE_REF, [bookId, chapter, verse, text], whereCreate3, null, 'whereCreate');
        }
    }
}
function whereCreate3(fSuccess, resultText, vNull) {
    var refId = stdFormatGetValue(resultText, 0, 0);
    if (refId) {
        ge("whereEditResult").innerHTML = "Successful creation of where #" + refId;
        whereFilterUpdate(refId);
        ge('whereFilteredList').value = refId;
    }
}
function whereUpdate() {
    // get form values
    var bookId = ge('whereEditBookList').value;
    var chapter = ge("whereEditChapter").value;
    var verse = ge("whereEditVerse").value;
    var text = ge("whereEditText").value;
    // validate values
    var sResult = "";
    if (chapter != Math.floor(Number(chapter))) {
        sResult = "Book chapter must be a number.";
    } else if (chapter == "") {
        sResult = "You must specify a chapter number.";
    } else if (verse != Math.floor(Number(verse))) {
        sResult = "Book verse must be a number.";
    } else if (verse == "") {
        sResult = "You must specify a verse number.";
    } 
    if (sResult) {
        ge('whereEditResult').innerHTML = sResult;
    } else {
        doQuery(Q_UPD_BIBLE_REF_TEXT_BY_BOOK_CHAPTER_AND_VERSE, [text, bookId, chapter, verse], whereUpdate2, null, 'whereUpdate');
    }
}
function whereUpdate2(fSuccess, resultText, vNull) {
    if (fSuccess) {
        var refId = ge('whereEditId').value;
        ge('whereEditResult').innerHTML = 'Successfully Updated bible reference #' + refId;
        ge('whereFilteredList').value = refId;
    } else {
        ge('whereEditResult').innerHTML = resultText;
    }
}
function whereDelete() {
    // get form values
    var bookId = ge('whereEditBookList').value;
    var chapter = ge("whereEditChapter").value;
    var verse = ge("whereEditVerse").value;
    // validate values
    var sResult = "";
    if (chapter != Math.floor(Number(chapter))) {
        sResult = "Book chapter must be a number.";
    } else if (chapter == "") {
        sResult = "You must specify a chapter number.";
    } else if (verse != Math.floor(Number(verse))) {
        sResult = "Book verse must be a number.";
    } else if (verse == "") {
        sResult = "You must specify a verse number.";
    } else {
        // Add the new reference
        doQuery(Q_DEL_BIBLE_REF, [bookId, chapter, verse], whereDelete2, null, 'whereDelete');
    }
}
function whereDelete2(fSuccess, resultText, vNull) {
    whereFilterUpdate(0);
    ge('whereEditResult').innerHTML = "Delete Successful";
}
function whereEditKeyChanged() {
    var chapter = ge("whereEditChapter").value;
    var verse = ge("whereEditVerse").value;
    // validate values
    var sResult = "";
    if (chapter != Math.floor(Number(chapter))) {
        sResult = "Book chapter must be a number.";
    } else if (chapter == "") {
        sResult = "You must specify a chapter number.";
    } else if (verse != Math.floor(Number(verse))) {
        sResult = "Book verse must be a number.";
    } else if (verse == "") {
        sResult = "You must specify a verse number.";
    }
    ge('whereEditResult').innerHTML = sResult;
    if (!sResult) {
        doQuery(Q_SEL_BIBLE_REF_BY_KEYS, [ge('whereEditBookList').value, chapter, verse], whereEditKeyChanged2, '', 'whereEditKeyChanged');
    }
}
function whereEditKeyChanged2(fSuccess, responseText, vNull) {
    var refId = stdFormatGetValue(responseText, 0, 0);
    fExists = (refId != '');
    ge('whereCreateBtn').disabled = fExists;
    ge('whereUpdateBtn').disabled = !fExists;    // key match exists - this could be wrong
    ge('whereDeleteBtn').disabled = !fExists;
    if (fExists) {
        ge('whereFilteredList').value = refId;
        whereEditFill(refId);
    } else {
        ge('whereEditText').value = '';
        ge('whereEditId').value = '';
    }
}
function whereEditValueChanged() {
    doQuery(Q_SEL_BIBLE_REF_BY_KEYS, [ge('whereEditBookList').value, 
                                      ge('whereEditChapter').value, 
                                      ge('whereEditVerse').value], 
                                      whereEditValueChanged2, '', 'whereEditValueChanged');
}
function whereEditValueChanged2(fSuccess, responseText, vNull) {
    fKeysExist = (stdFormatGetValue(responseText, 0, 0) != '');
    ge('whereCreateBtn').disabled = fKeysExist;
    ge('whereDeleteBtn').disabled = !fKeysExist;
    doQuery(Q_SEL_BIBLE_REF_BY_KEYS_AND_VALUES, [ge('whereEditBookList').value, 
                                                 ge('whereEditChapter').value, 
                                                 ge('whereEditVerse').value, 
                                                 ge('whereEditText').value], 
                                                 whereEditValueChanged3, fKeysExist, 'whereEditValueChanged2');
}
function whereEditValueChanged3(fSuccess, responseText, fKeysExist) {
    fExactMatchExists = (stdFormatGetValue(responseText, 0, 0) != '');
    ge('whereUpdateBtn').disabled = fExactMatchExists || !fKeysExist;
}
function whereEditFill(refId) {
    if (!refId) {
        refId = ge('whereFilteredList').value;
    }
    if (refId && (refId == Number(refId))) {
        doQuery(Q_SEL_BIBLE_REF_IN_REFID, [refId], whereEditFill2, '', 'whereEditFill - filtered by bookid');
    } else {
        doQuery(Q_SEL_BIBLE_REF, '', whereEditFill2, '', 'whereEditFill - unfiltered');
    }
}
function whereEditFill2(fSuccess, responseText, vNull) {
    ge('whereEditId').value = stdFormatGetValue(responseText, 0, 0);
    ge('whereEditBookList').value = stdFormatGetValue(responseText, 0, 1);
    ge('whereEditChapter').value = stdFormatGetValue(responseText, 0, 2);
    ge('whereEditVerse').value = stdFormatGetValue(responseText, 0, 3);
    ge('whereEditText').value = stdFormatGetValue(responseText, 0, 4);
    ge('whereEditResult').innerHTML = '';
    ge('whereCreateBtn').disabled = true;
    ge('whereUpdateBtn').disabled = true;
    ge('whereDeleteBtn').disabled = false;
}
function whereFilterUpdate(refIdNew) {
    var whereFilterText = ge('whereFilterText').value;
    var bookId = ge('whereFilterBookSelect').value;
    if (bookId < 0) {   
        // any book selected
        doQuery(Q_SEL_BIBLE_REF_ALL_IN_TEXT, [whereFilterText], whereFilterUpdate2, refIdNew, 'whereFilterUpdate - text filtered');
    } else {
        doQuery(Q_SEL_BIBLE_REF_ALL_IN_BOOK_AND_TEXT, [bookId, whereFilterText], whereFilterUpdate2, refIdNew, 'whereFilterUpdate - id+text filtered');
    }
}
function whereFilterUpdate2(fSuccess, resultText, refIdNew) {
    var eS = ge("whereFilteredList");
    applyEToSelect(fSuccess, resultText, eS);
    if (eS.options.length > 0) {
        if (refIdNew) {
            ge('whereFilteredList').value = refIdNew;
            ge('whereEditId').value = refIdNew;
        } else {
            ge('whereFilteredList').selectedIndex = 0;
            whereEditFill(0);
        }
    }
}
// ------------------------------------------------ Bible Ref Functions --------------------------------------------------
function bibleRefCreate() {
    // get Key Edit form values
    var bookId = ge('bibleRefEditBookList').value;
    var chapter = ge("bibleRefEditChapter").value;
    var verse = ge("bibleRefEditVerse").value;

    // see if a bibleRef with these keys already exists
    doQuery(Q_SEL_BIBLE_REF_BY_KEYS, [bookId, chapter, verse], bibleRefCreate2, null, 'bibleRefCreate')
}
function bibleRefCreate2(fSucess, resultText, vNull) {
    var existingId = stdFormatGetValue(resultText, 0, 0);
    var fExists = (existingId != '');
    if (fExists) {
        ge('bibleRefEditId').value = existingId;
        bibleRefUpdate();
    } else {
        var bookId = ge('bibleRefEditBookList').value;
        var chapter = ge("bibleRefEditChapter").value;
        var verse = ge("bibleRefEditVerse").value;
        var text = ge("bibleRefEditText").value;
        var sResult = "";
        if (chapter != Math.floor(Number(chapter))) {
            sResult = "Book chapter must be a number.";
        } else if (chapter == "") {
            sResult = "You must specify a chapter number.";
        } else if (verse != Math.floor(Number(verse))) {
            sResult = "Book verse must be a number.";
        } else if (verse == "") {
            sResult = "You must specify a verse number.";
        } 
        if (sResult) {
            ge("bibleRefEditResult").innerHTML = sResult;
        } else {
            doQuery(Q_INS_BIBLE_REF, [bookId, chapter, verse, text], bibleRefCreate3, null, 'bibleRefCreate');
        }
    }
}
function bibleRefCreate3(fSuccess, resultText, vNull) {
    var refId = stdFormatGetValue(resultText, 0, 0);
    if (refId) {
        ge("bibleRefEditResult").innerHTML = "Successful creation of bibleRef #" + refId;
        bibleRefFilterUpdate(refId);
        ge('bibleRefFilteredList').value = refId;
    }
}
function bibleRefUpdate() {
    // get form values
    var bookId = ge('bibleRefEditBookList').value;
    var chapter = ge("bibleRefEditChapter").value;
    var verse = ge("bibleRefEditVerse").value;
    var text = ge("bibleRefEditText").value;
    // validate values
    var sResult = "";
    if (chapter != Math.floor(Number(chapter))) {
        sResult = "Book chapter must be a number.";
    } else if (chapter == "") {
        sResult = "You must specify a chapter number.";
    } else if (verse != Math.floor(Number(verse))) {
        sResult = "Book verse must be a number.";
    } else if (verse == "") {
        sResult = "You must specify a verse number.";
    } 
    if (sResult) {
        ge('bibleRefEditResult').innerHTML = sResult;
    } else {
        doQuery(Q_UPD_BIBLE_REF_TEXT_BY_BOOK_CHAPTER_AND_VERSE, [text, bookId, chapter, verse], bibleRefUpdate2, null, 'bibleRefUpdate');
    }
}
function bibleRefUpdate2(fSuccess, resultText, vNull) {
    if (fSuccess) {
        var refId = ge('bibleRefEditId').value;
        ge('bibleRefEditResult').innerHTML = 'Successfully Updated bible reference #' + refId;
        ge('bibleRefFilteredList').value = refId;
    } else {
        ge('bibleRefEditResult').innerHTML = resultText;
    }
}
function bibleRefDelete() {
    // get form values
    var bookId = ge('bibleRefEditBookList').value;
    var chapter = ge("bibleRefEditChapter").value;
    var verse = ge("bibleRefEditVerse").value;
    // validate values
    var sResult = "";
    if (chapter != Math.floor(Number(chapter))) {
        sResult = "Book chapter must be a number.";
    } else if (chapter == "") {
        sResult = "You must specify a chapter number.";
    } else if (verse != Math.floor(Number(verse))) {
        sResult = "Book verse must be a number.";
    } else if (verse == "") {
        sResult = "You must specify a verse number.";
    } else {
        // Add the new reference
        doQuery(Q_DEL_BIBLE_REF, [bookId, chapter, verse], bibleRefDelete2, null, 'bibleRefDelete');
    }
}
function bibleRefDelete2(fSuccess, resultText, vNull) {
    bibleRefFilterUpdate(0);
    ge('bibleRefEditResult').innerHTML = "Delete Successful";
}
function bibleRefEditKeyChanged() {
    var chapter = ge("bibleRefEditChapter").value;
    var verse = ge("bibleRefEditVerse").value;
    // validate values
    var sResult = "";
    if (chapter != Math.floor(Number(chapter))) {
        sResult = "Book chapter must be a number.";
    } else if (chapter == "") {
        sResult = "You must specify a chapter number.";
    } else if (verse != Math.floor(Number(verse))) {
        sResult = "Book verse must be a number.";
    } else if (verse == "") {
        sResult = "You must specify a verse number.";
    }
    ge('bibleRefEditResult').innerHTML = sResult;
    if (!sResult) {
        doQuery(Q_SEL_BIBLE_REF_BY_KEYS, [ge('bibleRefEditBookList').value, chapter, verse], bibleRefEditKeyChanged2, '', 'bibleRefEditKeyChanged');
    }
}
function bibleRefEditKeyChanged2(fSuccess, responseText, vNull) {
    var refId = stdFormatGetValue(responseText, 0, 0);
    fExists = (refId != '');
    ge('bibleRefCreateBtn').disabled = fExists;
    ge('bibleRefUpdateBtn').disabled = !fExists;    // key match exists - this could be wrong
    ge('bibleRefDeleteBtn').disabled = !fExists;
    if (fExists) {
        ge('bibleRefFilteredList').value = refId;
        bibleRefEditFill(refId);
    } else {
        ge('bibleRefEditText').value = '';
        ge('bibleRefEditId').value = '';
    }
}
function bibleRefEditValueChanged() {
    doQuery(Q_SEL_BIBLE_REF_BY_KEYS, [ge('bibleRefEditBookList').value, 
                                      ge('bibleRefEditChapter').value, 
                                      ge('bibleRefEditVerse').value], 
                                      bibleRefEditValueChanged2, '', 'bibleRefEditValueChanged');
}
function bibleRefEditValueChanged2(fSuccess, responseText, vNull) {
    fKeysExist = (stdFormatGetValue(responseText, 0, 0) != '');
    ge('bibleRefCreateBtn').disabled = fKeysExist;
    ge('bibleRefDeleteBtn').disabled = !fKeysExist;
    doQuery(Q_SEL_BIBLE_REF_BY_KEYS_AND_VALUES, [ge('bibleRefEditBookList').value, 
                                                 ge('bibleRefEditChapter').value, 
                                                 ge('bibleRefEditVerse').value, 
                                                 ge('bibleRefEditText').value], 
                                                 bibleRefEditValueChanged3, fKeysExist, 'bibleRefEditValueChanged2');
}
function bibleRefEditValueChanged3(fSuccess, responseText, fKeysExist) {
    fExactMatchExists = (stdFormatGetValue(responseText, 0, 0) != '');
    ge('bibleRefUpdateBtn').disabled = fExactMatchExists || !fKeysExist;
}
function bibleRefEditFill(refId) {
    if (!refId) {
        refId = ge('bibleRefFilteredList').value;
    }
    if (refId && (refId == Number(refId))) {
        doQuery(Q_SEL_BIBLE_REF_IN_REFID, [refId], bibleRefEditFill2, '', 'bibleRefEditFill - filtered by bookid');
    } else {
        doQuery(Q_SEL_BIBLE_REF, '', bibleRefEditFill2, '', 'bibleRefEditFill - unfiltered');
    }
}
function bibleRefEditFill2(fSuccess, responseText, vNull) {
    ge('bibleRefEditId').value = stdFormatGetValue(responseText, 0, 0);
    ge('bibleRefEditBookList').value = stdFormatGetValue(responseText, 0, 1);
    ge('bibleRefEditChapter').value = stdFormatGetValue(responseText, 0, 2);
    ge('bibleRefEditVerse').value = stdFormatGetValue(responseText, 0, 3);
    ge('bibleRefEditText').value = stdFormatGetValue(responseText, 0, 4);
    ge('bibleRefEditResult').innerHTML = '';
    ge('bibleRefCreateBtn').disabled = true;
    ge('bibleRefUpdateBtn').disabled = true;
    ge('bibleRefDeleteBtn').disabled = false;
}
function bibleRefFilterUpdate(refIdNew) {
    var bibleRefFilterText = ge('bibleRefFilterText').value;
    var bookId = ge('bibleRefFilterBookSelect').value;
    if (bookId < 0) {   
        // any book selected
        doQuery(Q_SEL_BIBLE_REF_ALL_IN_TEXT, [bibleRefFilterText], bibleRefFilterUpdate2, refIdNew, 'bibleRefFilterUpdate - text filtered');
    } else {
        doQuery(Q_SEL_BIBLE_REF_ALL_IN_BOOK_AND_TEXT, [bookId, bibleRefFilterText], bibleRefFilterUpdate2, refIdNew, 'bibleRefFilterUpdate - id+text filtered');
    }
}
function bibleRefFilterUpdate2(fSuccess, resultText, refIdNew) {
    var eS = ge("bibleRefFilteredList");
    applyEToSelect(fSuccess, resultText, eS);
    if (eS.options.length > 0) {
        if (refIdNew) {
            ge('bibleRefFilteredList').value = refIdNew;
            ge('bibleRefEditId').value = refIdNew;
        } else {
            ge('bibleRefFilteredList').selectedIndex = 0;
            bibleRefEditFill(0);
        }
    }
}
// ------------------------------------------------ Comment Functions --------------------------------------------------
function commentCreate() {
    var commentEditText = ge("commentEditText").value;    
    // make sure an identical comment doesn't already exist
    doQuery(Q_SEL_COMMENT_BY_KEYS, [commentEditText], commentCreate2, commentEditText, 'commentCreate');  // pass existing commentEditId to create2
}
function commentCreate2(fSuccess, resultText, commentEditText) {
    var commentId = stdFormatGetValue(resultText, 0, 0);
    if (commentId == '') { // no preexisting comment - insert
        // doesn't already exist so insert it
        doQuery(Q_INS_COMMENT, [commentEditText], commentCreate3, commentEditText, 'commentCreate2');
    } else { // fake the insert as successful to create3
        commentCreate3(true, stdFormatTableToString([[commentId]]), commentEditText);
    }
}
function commentCreate3(fSuccess, resultText, commentEditText) {
    if (fSuccess) {
        // by the time we get here, the comment has been inserted or already existed.
        var commentId = stdFormatGetValue(resultText, 0, 0);
        commentFilterUpdate();  // update the filtered list of comments now that we have added one.
        ge("commentFilteredList").value = commentId;
        g.CreatedCommentId = commentId;
        g.CreatedCommentText = commentEditText;
    } else {
        ge("commentEditId").value = '';
        ge("commentEditText").value = '';
    }
}
function commentUpdate() {
    var commentEditText = ge("commentEditText").value;    
    var commentEditId = ge("commentEditId").value;
    doQuery(Q_UPD_COMMENT_IN_COMMENTID, [commentEditText, commentEditId], commentUpdate2, null, 'commentUpdate');
}
function commentUpdate2(fSuccess, responseText, vNull) {
    ge("commentEditResult").innerHTML = stdFormatGetValue(responseText, 0, 0);
    commentFilterUpdate();
}
function commentDelete() {
    var commentEditId = ge("commentEditId").value;
    doQuery(Q_DEL_COMMENT_IN_COMMENTID, [commentEditId], commentDelete2, null, 'commentDelete');
}
function commentDelete2(fSuccess, resultText, vNull) {
    ge("commentEditResult").innerHTML = stdFormatGetValue(resultText, 0, 0);
    commentFilterUpdate();
    commentEditClear();
}
function commentFilterUpdate() { // called to fill in the comment selector with filtered comments
    var commentTextContains = ge('commentTextContains').value;
    if (commentTextContains) {
        doQuery(Q_SEL_COMMENT_IN_TEXT, [commentTextContains], commentFilterUpdate2, null, 'commentFilterUpdate - text filtered');
    } else {
        doQuery(Q_SEL_COMMENT, '', commentFilterUpdate2, null, 'commentFilterUpdate - unfiltered');
    }
}
function commentFilterUpdate2(fSuccess, resultText, vNull) {
    applyToSelect(fSuccess, resultText, "commentFilteredList");
    if (g.CreatedCommentId) {
        ge('commentEditId').value = g.CreatedCommentId;
        delete g.CreatedCommentId;
        ge('commentEditText').value = g.CreatedCommentText;
        delete g.CreatedCommentText;
    } else {
        commentEditFill();
    }
}
function commentEditKeyChanged() {
    var text = ge('commentEditText').value;
    doQuery(Q_SEL_COMMENT_BY_KEYS, [text], commentEditKeyChanged2, '', 'commentEditTextChanged');
}
function commentEditKeyChanged2(fSuccess, resultText, vNull) {
    var commentId = stdFormatGetValue(resultText, 0, 0);
    var fExists = (commentId != '');                          
    if (fExists) {
        doQuery(Q_SEL_COMMENT_IN_ID, [commentId], commentEditFill2, '', 'commentEditFill');
    } else {
        ge('commentCreateBtn').disabled = false;
        ge('commentUpdateBtn').disabled = false;
        ge('commentDeleteBtn').disabled = true;
    }
}
function commentEditFill() {
    var commentId = ge('commentFilteredList').value;
    if (commentId) {
        doQuery(Q_SEL_COMMENT_IN_ID, [commentId], commentEditFill2, '', 'commentEditFill');
    }
}
function commentEditFill2(fSuccess, responseText, vNull) {    // apply comment edit data result to edit form
    ge('commentEditId').value = stdFormatGetValue(responseText, 0, 0);
    ge('commentEditText').value = stdFormatGetValue(responseText, 0, 1);
    ge('commentCreateBtn').disabled = true;
    ge('commentUpdateBtn').disabled = true;
    ge('commentDeleteBtn').disabled = false;
}
function commentEditClear() {
    ge('commentEditId').value = '';
    ge('commentEditText').value = '';
}
function ge(id) {
    if (!g.ids[id]) {
        g.ids[id] = document.getElementById(id);
    }
    return g.ids[id];
}
// override ajax error handler
ajax.errorHandler = function(sCallerInfo, sCalleeError) {
    ge("errorStatus").innerHTML = "Ajax Error:" + sCalleeError + ' from ' + sCallerInfo;
};
var smv = null;
function PageLoad() {
    smv = new singleItemMapViewer('map_canvas', setMapOutput);
    applyToSelect(true, "<?php echo formatQueryI(Q_SEL_WHAT_TYPE, ''); ?>", 'selWhatType');
    applyToSelect(true, "<?php echo formatQueryI(Q_SEL_BIBLE_BOOK, ''); ?>", 'bibleRefEditBookList');
    applyToSelectWithAnyOption(true, "<?php echo formatQueryI(Q_SEL_BIBLE_BOOK, ''); ?>", 'bibleRefFilterBookSelect');
    applyToSelect(true, "<?php echo formatQueryI(Q_SEL_BIBLE_REF_ALL_IN_BOOK, '1|'); ?>", 'bibleRefFilteredList');
    bibleRefEditFill(0);
    commentFilterUpdate();
}
  </script>
  <style type="text/css">
  html { height: 100% }      
  body { font-family:Arial; font-size:12pt; height="100%"}
  div { border:blue 3px solid; margin:5px; background-color:lightgray; }
  div.error { border:none; color:red; font-size:16pt; }
  h2,h3 { margin:0px; border:0px; }
  .ar { text-align:right; }
  .err { color:red; }
  .bd { border:1px solid blue; }
  .psec { height="14%" }
  .full { height="100%" width="100%" }
  .what     { border:black 0px solid; background-color: #b4A7A1; }
  .when     { border:black 0px solid; background-color: #C2C793; }
  .who      { border:black 0px solid; background-color: #A1A7C4; }
  .group    { border:black 0px solid; background-color: #7E8AC4; }
  .where    { border:black 0px solid; background-color: #A1C4AC; }
  .bibleRef { border:black 0px solid; background-color: #C4A1C2; }
  .comment  { border:black 0px solid; background-color: #6FB823; }
  .white    { border:black 0px solid; background-color: white; }
  </style>
</head>
<body onload="PageLoad();">
  <h1 align="center">TimeLine Data Entry</h1>
  <div class="error" id="errorStatus"></div>
  <div class="what psec"><!--------------------------------------- what --------------------------------------------------------->
    <table border="0" width="100%" class="full" cellspacing="0" cellpadding="6">
      <tr><td colspan="2" align="center"><h2>What Items</h2></td></tr>
      <tr>
        <td valign="top" width="50%" class="bd">
          <h3>Find</h3>
          Existing What Items:
          <select id="selWhats" onchange="fillWhat('selWhats');"></select>
          <button onclick="fillWhat('selWhats');">Edit this what item</button>
          <br>
          Relates to:<br>
          <div class="who"     ><input type="checkbox" class="who"     >Selected Who</input></div>
          <div class="when"    ><input type="checkbox" class="when"    >Selected When</input></div>
          <div class="group"   ><input type="checkbox" class="group"   >Selected Group</input></div>
          <div class="where"   ><input type="checkbox" class="where"   >Selected Where Item</input></div>
          <div class="bibleRef"><input type="checkbox" class="bibleRef">Selected Bible Reference</input></div>
          <div class="comment" ><input type="checkbox" class="comment" >Selected Comment</input></div>
          <div class="white"   ><input type="checkbox" class="white"   >Clear All cross group filters</input></div>
        </td>
        <td valign="top" width="50%" class="bd">
          <h3>Edit</h3>
          <div class="bd">
            <table valign="top" border="0">
              <tr>
                <td align="right">Type:</td>
                <td>
                  <select id="selWhatType"></select>
                  <input type="text" id="whatType" size="20"></input>
                  <input type="submit" value="Create New Type" onclick="whatCreateType();"/>
                </td>
              </tr>
              <tr>
                <td align="right">Name:</td>
                <td><input type="text" size="20" name="WhatName"></td>
              </tr>
              <tr>
                <td class="ar">Format:</td>
                <td><input type="text" size="40" name="WhatFormat"></td>
              </tr>
              <tr>
                <td class="ar">Inverse Format:</td>
                <td><input type="text" size="40" name="WhatInvFormat"></td>
              </tr>
              <tr>
                <td colspan="2">
                  <button onclick="createWhat();">Create</button>
                  <button onclick="updateWhat();">Update</button>
                  <button onclick="deleteWhat();">Delete</button><br>
                </td>
              </tr>
              <tr>
                <td colspan="2"><span id="whatResult"></span></td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="when psec"><!--------------------------------------- when --------------------------------------------------------->
    <table border="0" width="100%" class="full" cellspacing="0" cellpadding="6">
      <tr><td colspan="2" align="center"><h2>When Items</h2></td></tr>
      <tr>
        <td valign="top" width="50%" class="bd">
          <h3>Find</h3>
          Existing When Items:
          <select id="selWhens" onchange="fillWhen('selWhens');"></select>
          <button onclick="fillWhen('selWhens');">Edit this when item</button>
          <br>
          Relates to:<br>
          <div class="who"     ><input type="checkbox" class="who"     >Selected Who</input></div>
          <div class="group"   ><input type="checkbox" class="group"   >Selected Group</input></div>
          <div class="where"   ><input type="checkbox" class="where"   >Selected Where Item</input></div>
          <div class="bibleRef"><input type="checkbox" class="bibleRef">Selected Bible Reference</input></div>
          <div class="comment" ><input type="checkbox" class="comment" >Selected Comment</input></div>
          <div class="white"   ><input type="checkbox" class="white"   >Clear All cross group filters</input></div>
          <br>
        </td>
        <td valign="top" width="50%" class="bd">
          <h3>Edit</h3>
          <div class="bd">
            <table valign="top" border="0">
              <tr>
                <td align="right">Type:</td>
                <td>
                  <select id="selWhenType"></select>
                  <input type="text" size="20"></input>
               </td>
             </tr>
             <tr>
               <td align="right">Name:</td>
               <td><input type="text" size="20" name="WhenName"></td>
             </tr>
             <tr>
               <td align="right">Format:</td>
               <td><input type="text" size="40" name="WhenFormat"></td>
             </tr>
             <tr>
               <td align="right">Inverse Format:</td>
               <td><input type="text" size="40" name="WhenInvFormat"></td>
             </tr>
             <tr>
               <td colspan="2">
                 <button onclick="createWhen();">Create</button>
                 <button onclick="updateWhen();">Update</button>
                 <button onclick="deleteWhen();">Delete</button><br>
               </td>
             </tr>
             <tr>
               <td colspan="2"><span id="whenResult"></span></td>
             </tr>
           </table>
        </div>
      </tr>
    </table>
  </div>
  <div class="who psec"><!--------------------------------------- who --------------------------------------------------------->
    <table border="0" width="100%" class="full" cellspacing="0" cellpadding="6">
      <tr><td colspan="2" align="center"><h2>Who Items</h2></td></tr>
      <tr>
        <td valign="top" width="50%" class="bd">
          <h3>Find</h3>
          Existing Who Items:
          <select id="selWhos" onchange="fillWho('selWhos');"></select>
          <button onclick="fillWho('selWhos');">Edit this who item</button>
          <br>
          Relates to:<br>
          <div class="what"    ><input type="checkbox" class="what"    >Selected What Item</input></div>
          <div class="where"   ><input type="checkbox" class="where"   >Selected Where</input></div>
          <div class="group"   ><input type="checkbox" class="group"   >Selected Group</input></div>
          <div class="where"   ><input type="checkbox" class="where"   >Selected Where Item</input></div>
          <div class="bibleRef"><input type="checkbox" class="bibleRef">Selected Bible Reference</input></div>
          <div class="comment" ><input type="checkbox" class="comment" >Selected Comment</input></div>
          <div class="white"   ><input type="checkbox" class="white"   >Clear All cross group filters</input></div>
        </td>
        <td valign="top" width="50%" class="bd">
          <h3>Edit</h3>
          <div class="bd">
            <table valign="top" border="0">
              <tr>
                <td colspan="2">
                  <button onclick="createWho();">Create</button>
                  <button onclick="updateWho();">Update</button>
                  <button onclick="deleteWho();">Delete</button><br>
                </td>
              </tr>
              <tr>
                <td>
                  <span id="whoResult"></span>
                </td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="group psec"><!------------------------------------ group ------------------------------------------------------------>
    <table border="0" width="100%" class="full" cellspacing="0" cellpadding="6">
      <tr><td colspan="2" align="center"><h2>Group Items</h2></td></tr>
      <tr>
        <td valign="top" width="50%" class="bd">
          <h3>Find</h3>
          Existing Group Items:
          <select id="selGroups" onchange="fillGroup('selGroups');"></select>
          <button onclick="fillGroup('selGroups');">Edit this group item</button>
          <br>
          Relates to:<br>
          <div class="what"    ><input type="checkbox" class="what"    >Selected What Item</input></div>
          <div class="when"    ><input type="checkbox" class="when"    >Selected When</input></div>
          <div class="who"     ><input type="checkbox" class="who"     >Selected Who</input></div>
          <div class="where"   ><input type="checkbox" class="where"   >Selected Where Item</input></div>
          <div class="bibleRef"><input type="checkbox" class="bibleRef">Selected Bible Reference</input></div>
          <div class="comment" ><input type="checkbox" class="comment" >Selected Comment</input></div>
          <div class="white"   ><input type="checkbox" class="white"   >Clear All cross group filters</input></div>
        </td>
        <td valign="top" width="50%" class="bd">
          <h3>Edit</h3>
          <div class="bd">
            <table valign="top" border="0">
              <tr>
                <td colspan="2">
                  <button onclick="createGroup();">Create</button>
                  <button onclick="updateGroup();">Update</button>
                  <button onclick="deleteGroup();">Delete</button>
                </td>
              </tr>   
              <tr>
                <td colspan="2"><span id="groupResult"></span></td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="where psec"><!-------------------------------------- where ---------------------------------------------------------->
    <table border="0" width="100%" cellspacing="0" cellpadding="6" height="100%">
      <tr><td colspan="2" align="center"><h2>Where Items</h2></td></tr>
      <tr>
        <td valign="top" width="50%" class="bd">
          <h3>Find</h3>
          <div class="bd">
            <button onclick="whereFilterUpdate();" title="Click here to apply filter values to the list of existing items.">Apply Filters</button><br>
            Text Contains:<input id="whereFilterText" title="Enter text to only see where items that contain the entered text in the existing items list." onkeyup="whereFilterUpdate();" type="text" size="30"></input><br>
            Relates to:<br>
            <div class="what"    ><input type="checkbox" class="what"    >Selected What Item</input></div>
            <div class="when"    ><input type="checkbox" class="when"    >Selected When</input></div>
            <div class="who"     ><input type="checkbox" class="who"     >Selected Who</input></div>
            <div class="group"   ><input type="checkbox" class="group"   >Selected Group</input></div>
            <div class="bibleRef"><input type="checkbox" class="bibleRef">Selected Bible Reference</input></div>
            <div class="comment" ><input type="checkbox" class="comment" >Selected Comment</input></div>
            <div class="white"   ><input type="checkbox" class="white"   >Clear All cross group filters</input></div>
            <button onclick="whereEditFill();" title="Click here to fill in the edit fields with values corresponding to the selected item.">Select to Edit</button>
            <select id="whereFilteredList" onchange="whereEditFill();" title="Select which existing item you wish to view, reference or edit."></select>
           </div>
        </td>
        <td valign="top" width="50%" class="bd">
          <h3>Edit</h3>
          <div class="bd">
            <table border="0" width="100%">
              <tr>
                <td align="right">Id:</td>
                <td><input type="text" disabled="true" id="whereEditId"></input></td>
              </tr>
              <tr>
                <td align="right">Location:</td>
                <td width="100%" height="200" id="map_canvas"></td>
              </tr>
              <tr>
                <td colspan="2">
                  <button id="whereCreateBtn" onclick="whereCreate();" title="Click here to create a new location having the entered values.">Create</button>
                  <button id="whereUpdateBtn" onclick="whereUpdate();" title="Click here to apply changes you have made to the entered values to the location.">Update</button>
                  <button id="whereDeleteBtn" onclick="whereDelete();" title="Click here to delete this location from the database.  Note that this will fail if any other items reference this item.">Delete</button>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <span id="whereEditResult"></span>
                  <input id="whereLocation" type="hidden">
                </td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="bibleRef psec"><!-------------------------------------- bibleRef ---------------------------------------------------------->
    <table border="0" width="100%" class="full" cellspacing="0" cellpadding="6">
      <tr><td colspan="2" align="center"><h2>Bible References</h2></td></tr>
      <tr>
        <td valign="top" width="50%" class="bd">
          <h3>Find</h3>
          <div class="bd">
            <button onclick="bibleRefFilterUpdate();" title="Click here to apply filter values to the list of existing items.">Apply Filters</button><br>
            Reference is in Book:<select id="bibleRefFilterBookSelect" title="Select a book to narrow down the list of existing items or choose ANY to not filter on the book." onchange="bibleRefFilterUpdate();"></select><br>
            Text Contains:<input id="bibleRefFilterText" title="Enter text to only see verses that contain the entered text in the existing items list." onchange="bibleRefFilterUpdate();" type="text" size="30"></input><br>
            Relates to:<br>
            <div class="what"    ><input type="checkbox" class="what"    id="bibleRefFilterRelatedWhat">Selected What Item</input></div>
            <div class="when"    ><input type="checkbox" class="when"    id="bibleRefFilterRelatedWhen">Selected When</input></div>
            <div class="who"     ><input type="checkbox" class="who"     id="bibleRefFilterRelatedWho">Selected Who</input></div>
            <div class="group"   ><input type="checkbox" class="group"   id="bibleRefFilterRelatedGroup">Selected Group</input></div>
            <div class="where"   ><input type="checkbox" class="where"   id="bibleRefFilterRelatedWhere">Selected Where Item</input></div>
            <div class="comment" ><input type="checkbox" class="comment" id="bibleRefFilterRelatedComment">Selected Comment</input></div>
            <div class="white"   ><input type="checkbox" class="white">Clear All cross group filters</input></div>
            <button onclick="bibleRefEditFill();" title="Click here to fill in the edit fields with values corresponding to the selected item.">Select to Edit</button>
            <select id="bibleRefFilteredList" onchange="bibleRefEditFill();" title="Select which existing item you wish to view, reference or edit."></select>
          </div>
        </td>
        <td valign="top" width="50%" class="bd">
          <h3>Edit</h3>
           <div class="bd">
            <table border="0">
               <tr>
                 <td align="right">Id:</td>
                 <td><input type="text" disabled="true" id="bibleRefEditId"></input></td>
               </tr>
              <tr>
                <td align="right">Book:</td>
                <td><select id="bibleRefEditBookList" title="Enter the book this reference is found in." onchange="bibleRefEditKeyChanged();"></select></td>
              </tr>
              <tr>
                <td align="right">Chapter:</td>
                <td><input id="bibleRefEditChapter" type="text" size="4" title="Enter the chapter this reference is found in." onkeyup="bibleRefEditKeyChanged();"></input></td>
              </tr>
              <tr>
                <td align="right">Verse:</td>
                <td><input id="bibleRefEditVerse" type="text" size="6" title="Enter the verse for this reference." onkeyup="bibleRefEditKeyChanged();"></input></td>
              </tr>
              <tr>
                <td align="right">Text:</td>
                <td><textarea id="bibleRefEditText" cols="40" rows="8" value="" title="Enter the text of the reference here." onkeyup="bibleRefEditValueChanged();"></textarea></td>
              </tr>
            </table>
            <button id="bibleRefCreateBtn" onclick="bibleRefCreate();" title="Click here to create a new reference having the entered values.">Create</button>
            <button id="bibleRefUpdateBtn" onclick="bibleRefUpdate();" title="Click here to apply changes you have made to the entered values to the reference.">Update</button>
            <button id="bibleRefDeleteBtn" onclick="bibleRefDelete();" title="Click here to delete this reference from the database.  Note that this will fail if any other items reference this item.">Delete</button><br>
            <span id="bibleRefEditResult"></span>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="comment psec"><!-------------------------------------- comment ---------------------------------------------------------->
    <table border="0" width="100%" cellspacing="0" cellpadding="6">
      <tr><td colspan="2" align="center"><h2>Comments</h2></td></tr>
      <tr>
        <td valign="top" width="50%" class="bd">
          <h3>Find</h3>
          <div class="bd">
            <button onclick="commentFilterUpdate();" title="Click here to apply filter values to the list of existing items.">Apply Filters</button><br>
            Text Contains:<input id="commentTextContains" title="Enter text to only see comments that contain the entered text in the existing items list." onchange="commentFilterUpdate();" type="text" size="30"></input><br>
            Relates to:<br>
            <div class="what"    ><input type="checkbox" class="what"    id="commentFilterRelatedWhat">Selected What Item</input></div>
            <div class="when"    ><input type="checkbox" class="when"    id="commentFilterRelatedWhen">Selected When</input></div>
            <div class="who"     ><input type="checkbox" class="who"     id="commentFilterRelatedWho">Selected Who</input></div>
            <div class="group"   ><input type="checkbox" class="group"   id="commentFilterRelatedGroup">Selected Group</input></div>
            <div class="where"   ><input type="checkbox" class="where"   id="commentFilterRelatedWhere">Selected Where Item</input></div>
            <div class="bibleRef"><input type="checkbox" class="bibleRef" id="commentFilterRelatedBibleRef">Selected Bible Reference</input></div>
            <div class="white"   ><input type="checkbox" class="white">Clear All cross group filters</input></div>
            <button onclick="commentEditFill();">Select to Edit</button>
            <select id="commentFilteredList" onchange="commentEditFill();"></select>
          </div>
        </td>
        <td valign="top" width="50%" class="bd">
          <h3>Edit</h3>
           <div class="bd">
             <table border="0">
               <tr>
                 <td align="right">Id:</td>
                 <td><input type="text" disabled="true" id="commentEditId"></input></td>
               </tr>
               <tr>
                 <td align="right">Text:</td>
                 <td><textarea id="commentEditText" onkeyup="commentEditKeyChanged();" cols="40" rows="8" value="" title="Enter the text of the comment here."></textarea></td>
               </tr>
             </table>
             <button id="commentCreateBtn" onclick="commentCreate();">Create</button>
             <button id="commentUpdateBtn" onclick="commentUpdate();">Update</button>
             <button id="commentDeleteBtn" onclick="commentDelete();">Delete</button><br>
             <span id="commentEditResult"></span>
          </div>
        </td>
      </tr>
    </table>
  </div>
</html>


