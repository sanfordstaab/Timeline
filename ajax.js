// ajax.js
//
// My special implementation of AJAX
//
var ajax = {
  requestCache:[],
  cRequestsInUse:0,
  // 
  // Error handling:
  //    Ajax can fail due to loss of connection with the server or other problems on the server side.
  //    In order to allow the client to get some kind of error info back from an Ajax Call,
  //    The ajax handler on the server side should return a string starting with "Error:"
  //    if there is a problem which can be used to inform the user of a problem.
  //    The caller should supply the sCallerInfo parameter to ajax.post to assist in debuging a problem.
  //
  failCheck:function(fSuccess, responseText, sCallerInfo) {
      var sCalleeError = '';
      var fCalleeError = (responseText.indexOf("Error:") == 0);
      if (fCalleeError) {
          sCalleeError = responseText;
      }
      var fFailed = (!fSuccess || fCalleeError);
      if (fFailed) {
          ajax.errorHandler(sCallerInfo, sCalleeError);
      }
      return !fFailed;
  }, // ajax.failCheck
  errorHandler:function(sCallerInfo, sCalleeError) {
       alert("Ajax Failure: " + sCallerInfo + ', ' + sCalleeError);
  }, // ajax.errorHandler - override with application as necessary
  post:function(ajaxURL,       // ajax URL
                fnContinue,    // function to call when ajax call has returned or null for no continuation
                continueInfo,  // info to supply fnContinue when ajax call has returned
                sCallerInfo) { // debuging info to use on ajax failure alert popup.
      var req;

      if (ajax.requestCache.length) {
          req = ajax.requestCache.pop();
      } else {
          if (window.XMLHttpRequest) {     
              // Standard object - Firefox, Safari, ...
              req = new XMLHttpRequest();
          } else // assume if (window.ActiveXObject)
          {    
              // Internet Explorer 
              req = new ActiveXObject("Microsoft.XMLHTTP");
          }
      }
      ajax.cRequestsInUse++;
      req.onreadystatechange = function() {          
          if (req.readyState  == 4) {              
              var fSuccess = ajax.failCheck(req.status == 200, req.responseText, sCallerInfo);
              if (fnContinue) {
                  if (req.status  == 200) {
                      fnContinue(fSuccess, req.responseText, continueInfo);
                  } else {
                      fnContinue(false, req.status, continueInfo);
                  }    
              }
              ajax.requestCache.push(req);
              ajax.cRequestsInUse--;
          }
      }
      var i = ajaxURL.indexOf('?');
      if (i == -1) {
          req.open('POST', ajaxURL, true);
          req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          req.send('');
      } else {
          req.open('POST', ajaxURL.substr(0, i), true);
          req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          req.send(ajaxURL.substring(i + 1));
      }
  } // ajax.post
}; // ajax
