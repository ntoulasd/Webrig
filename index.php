<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Webrig</title>
<script type="text/javascript" src="jquery.min.js"></script>
        <script src="//cdn.webrtc-experiment.com/firebase.js"> </script>
        <script src="//cdn.webrtc-experiment.com/getMediaElement.min.js"> </script>
        <script src="//cdn.webrtc-experiment.com/RTCMultiConnection.js"> </script>
<script>
setInterval(function(){
      $('#signals').load('signals.php');
 },1000);
</script>
<style>
body {
background: rgb(136,191,232); 
}
#ptt {
color:red;
width:300px;
height:150px;
font-size:24px;
background: rgb(181,189,200); /* Old browsers */
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
border-radius: 12px;
}
#set {
color:red;
width:200px;
height:50px;
font-size:18px;
background: rgb(181,189,200); /* Old browsers */
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
border-radius: 12px;
}
#minus, #plus {
color:red;
width:70px;
height:50px;
font-size:18px;
background: rgb(181,189,200); /* Old browsers */
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
border-radius: 12px;
}
</style>
</head>
<body>

<center>
<?php
include ('config.php');
// Read rig
$freq = exec('rigctl  -m 2 -r '.HOST.' f');
$mod = exec('rigctl  -m 2 -r '.HOST.' m | head -n 1');


?>
<h2>
<div id="signals"></div>
</h2>

<button id="ptt" onclick="ptt()">PTT</button> 
<br><br>
<script>
function ptt(){
$('#ptt').load('ptt.php');
}; 
function set(){
freq=document.getElementById("freq").value;
mod=document.getElementById("mod").value;
mem=document.getElementById("mem").value;
$('#set').load('set.php?freq='+freq+'&mod='+mod+'&mem='+mem);
document.getElementById("mem").value = "";
document.getElementById("freq").value = "";
}; 
function minus(){
$('#set').load('set.php?move=minus');
}; 
function plus(){
$('#set').load('set.php?move=plus');
}; 
</script>

<input id="freq" type="text" name="freq" value="<?php echo $freq;?>">
<br>

 <select id="mod" name="mod">
  <option <?php if ($mod=="FM") { echo 'selected="selected"'; } ?> value="FM">FM</option>
  <option <?php if ($mod=="USB") { echo 'selected="selected"'; } ?> value="USB">USB</option>
  <option <?php if ($mod=="LSB") { echo 'selected="selected"'; } ?> value="LSB">LSB</option>
  <option <?php if ($mod=="AM") { echo 'selected="selected"'; } ?> value="AM">AM</option>
</select> 


<select id="mem" name="mem">
  <option value="">Memory</option>
  <option value="M1">RU-90</option>
  <option value="M2">RU-72</option>
  <option value="M3">Radio 91.6</option>
  <option value="M4">7.185 LSB</option>
  <option value="M5">Katerini</option>
  <option value="M6">SOTA</option>
  <option value="M7">ISS</option>
  <option value="M8">R0 Lamia</option>
  <option value="M9">R5 Pilio</option>
  <option value="M10">RU92</option>
</select> 
<br>
<br>

<button id="minus" onclick="minus()">-12.5</button> <button id="set" onclick="set()">SET</button> <button id="plus" onclick="plus()">+12.5</button>
<br><br>

<!-- just copy this <section> and next script -->
            <section class="experiment">
				<section>

                    
                    <input type="text" id="user-name" placeholder="Your Callsign">
                    <button id="setup-voice-only-call" class="setup">Audio Stream</button>
                </section>
				
                <!-- list of all available broadcasting rooms -->
                <table style="width: 100%;" id="rooms-list"></table>
                
                <!-- local/remote videos container -->
                <div id="audios-container"></div>
            </section>
</center>
        
            <script>

                var connection = new RTCMultiConnection();
                connection.session = {
                    audio: true, video: false
                };
                
               connection.bandwidth = { audio: 6 };

                // Find the line in sdpLines that starts with |prefix|, and, if specified,
                // contains |substr| (case-insensitive search).
                function findLine(sdpLines, prefix, substr) {
                  return findLineInRange(sdpLines, 0, -1, prefix, substr);
                }

                // Find the line in sdpLines[startLine...endLine - 1] that starts with |prefix|
                // and, if specified, contains |substr| (case-insensitive search).
                function findLineInRange(sdpLines, startLine, endLine, prefix, substr) {
                  var realEndLine = endLine !== -1 ? endLine : sdpLines.length;
                  for (var i = startLine; i < realEndLine; ++i) {
                    if (sdpLines[i].indexOf(prefix) === 0) {
                      if (!substr ||
                          sdpLines[i].toLowerCase().indexOf(substr.toLowerCase()) !== -1) {
                        return i;
                      }
                    }
                  }
                  return null;
                }

                // Gets the codec payload type from an a=rtpmap:X line.
                function getCodecPayloadType(sdpLine) {
                  var pattern = new RegExp('a=rtpmap:(\\d+) \\w+\\/\\d+');
                  var result = sdpLine.match(pattern);
                  return (result && result.length === 2) ? result[1] : null;
                }

                var roomsList = document.getElementById('rooms-list'), sessions = { };
                connection.onNewSession = function(session) {
                    if (sessions[session.sessionid]) return;
                    sessions[session.sessionid] = session;

                    var tr = document.createElement('tr');
                    tr.innerHTML = '<td><strong>' + ((session.extra && session.extra['user-name']) || session.userid) + '</strong> is making an audio call.</td>' +
                        '<td><button class="join" id="receive-call">Receive Call</button></td>';
                    roomsList.insertBefore(tr, roomsList.firstChild);

                    tr.querySelector('#receive-call').setAttribute('data-sessionid', session.sessionid);
                    tr.querySelector('#receive-call').onclick = function() {
                        this.disabled = true;

                        session = sessions[this.getAttribute('data-sessionid')];
                        if (!session) alert('No room to join.');

                        connection.join(session);
                    };
                };

                var audiosContainer = document.getElementById('audios-container') || document.body;
                connection.onstream = function(e) {
					var audioElement = getAudioElement(e.mediaElement, {
						title: (e.extra && e.extra['user-name']) || e.userid,
						onMuted: function(type) {
                            connection.streams[e.streamid].mute({
                                audio: type == 'audio',
                                video: type == 'video'
                            });
                        },
                        onUnMuted: function(type) {
                            connection.streams[e.streamid].unmute({
                                audio: type == 'audio',
                                video: type == 'video'
                            });
                        },
                        onRecordingStarted: function(type) {
                            connection.streams[e.streamid].startRecording({
                                audio: type == 'audio',
                                video: type == 'video'
                            });
                        },
                        onRecordingStopped: function(type) {
                            connection.streams[e.streamid].stopRecording(function(blob) {
                                var _mediaElement = document.createElement(type);
                                
                                _mediaElement.src = URL.createObjectURL(blob);
                                _mediaElement = getMediaElement(_mediaElement, {
                                    buttons: ['mute-audio', 'mute-video', 'stop']
                                });
                                
                                _mediaElement.toggle(['mute-audio', 'mute-video']);
                                
                                audiosContainer.insertBefore(_mediaElement, audiosContainer.firstChild);
                            }, type);
                        },
                        onStopped: function() {
                            connection.drop();
                        }
					});
					
					if(e.type == 'local') {
						// audioElement.toggle('mute-audio');
                        e.mediaElement.volume = 0;
                        e.mediaElement.muted = true;
					}
					
                    audiosContainer.insertBefore(audioElement, audiosContainer.firstChild);
                };

                connection.onstreamended = function(e) {
                    if (e.mediaElement.parentNode && e.mediaElement.parentNode.parentNode && e.mediaElement.parentNode.parentNode.parentNode) {
                        e.mediaElement.parentNode.parentNode.parentNode.removeChild(e.mediaElement.parentNode.parentNode);
                    }
                };
				
				document.getElementById('user-name').onkeyup = function() {
					connection.extra['user-name'] = this.value;
				};

                document.getElementById('setup-voice-only-call').onclick = function() {
                    this.disabled = true;
                    connection.open();
                };
                
                connection.extra = {
                    'user-name': 'Anonymous'
                };

                connection.connect();
				
				(function() {
                    var uniqueToken = document.getElementById('unique-token');
                    if (uniqueToken)
                        if (location.hash.length > 2) uniqueToken.parentNode.parentNode.parentNode.innerHTML = '<h2 style="text-align:center;"><a href="' + location.href + '" target="_blank">Share this link</a></h2>';
                        else uniqueToken.innerHTML = uniqueToken.parentNode.parentNode.href = '#' + (Math.random() * new Date().getTime()).toString(36).toUpperCase().replace( /\./g , '-');
                })();
            </script>

</body>
</html>
