<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Main Gate Camera</title>

<style>
body {
    margin: 0;
    background: #0b1220;
    color: white;
    text-align: center;
    font-family: Arial, sans-serif;
    height: 100vh;
}
.top-bar { position:absolute; top:20px; right:30px; }
.home-btn {
    background:transparent; border:1px solid #1e90ff; color:white;
    padding:8px 16px; border-radius:6px; cursor:pointer;
}
.home-btn:hover { background:#1e90ff; }

h2 { margin-top:40px; }

button {
    margin:10px; padding:10px 22px; font-size:15px;
    border-radius:6px; border:none; cursor:pointer;
}
.start-btn { background:#16a34a; }
.stop-btn { background:#dc2626; }
.capture-btn { background:#2563eb; }

video {
    width:480px; border:2px solid #1e90ff;
    border-radius:8px; box-shadow:0 0 20px rgba(30,144,255,0.3);
}

.overlay {
    position:fixed; inset:0; background:rgba(0,0,0,.6);
    display:none; z-index:1000;
}
.popup {
    position:fixed; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:700px; background:#0b1220;
    border:2px solid #1e90ff; border-radius:10px;
    display:none; z-index:1001;
}
.popup-header {
    padding:14px; display:flex;
    justify-content:space-between;
    border-bottom:1px solid #1e90ff;
}
.popup-body { display:flex; padding:15px; }
.popup-photo { width:45%; }
.popup-photo img {
    width:100%; border-radius:8px;
    border:1px solid #1e90ff;
}
.popup-details { width:55%; padding-left:15px; text-align:left; }
.popup-details span { color:#93c5fd; }

.popup-footer {
    border-top:1px solid #1e90ff;
    padding:12px; text-align:right;
}
.confirm-btn { background:#16a34a; color:white; padding:8px 18px; }
.cancel-btn { background:#dc2626; color:white; padding:8px 18px; }

#errorMsg {
    color:#f87171;
    font-size:18px;
    margin-top:12px;
    display:none;
}
</style>
</head>

<body>

<div class="top-bar">
<button class="home-btn" onclick="location.href='/PR Project/Home/index.php'">Home</button>
</div>

<h2>Main Gate Camera</h2>

<button class="start-btn" onclick="startCamera()">Start Camera</button>
<button class="stop-btn" onclick="stopCamera()">Stop Camera</button>
<button class="capture-btn" onclick="capture()">Capture</button>

<div>
<video id="video" autoplay muted></video>
</div>

<div id="overlay" class="overlay" onclick="closePopup()"></div>

<div id="studentPopup" class="popup">
<div class="popup-header">
<h3>Student Verification</h3>
<span style="cursor:pointer" onclick="closePopup()">×</span>
</div>

<div class="popup-body">
<div class="popup-photo">
<img id="capturedImage">
</div>

<div class="popup-details">
<p><span>Name</span> : <b id="pName"></b></p>
<p><span>Roll</span> : <b id="pRoll"></b></p>
<p><span>Hostel</span> : <b id="pHostel"></b></p>
<p><span>Room</span> : <b id="pRoom"></b></p>
<p><span>Phone</span> : <b id="pPhone"></b></p>

<p id="statusRow">
<span>Status</span> : <b id="pStatus">Pending</b>
</p>

<p id="purposeBlock">
<span>Purpose</span> :
<select id="pPurpose" style="background:#0b1220;color:white;border:1px solid #1e90ff;border-radius:4px;padding:4px;">
<option value="">Select Purpose</option>
<option>Tea Break</option>
<option>Market</option>
<option>Hospital</option>
<option>Official Work</option>
<option>Vacation</option>
</select>
</p>

<div id="errorMsg">
Face not recognized.<br>Please contact hostel office.
</div>
</div>
</div>

<div class="popup-footer">
<button id="confirmBtn" class="confirm-btn" onclick="confirmEntry()">Confirm</button>
<button class="cancel-btn" onclick="closePopup()">Cancel</button>
</div>
</div>

<script>
let stream = null;
let currentRoll = null;
let faceMatched = false;

function startCamera() {
navigator.mediaDevices.getUserMedia({video:true})
.then(s => { stream=s; video.srcObject=s; })
.catch(()=>alert("Camera denied"));
}

function stopCamera() {
if(stream) stream.getTracks().forEach(t=>t.stop());
}

function capture() {
const c=document.createElement("canvas");
c.width=video.videoWidth;
c.height=video.videoHeight;
c.getContext("2d").drawImage(video,0,0);

c.toBlob(blob=>{
capturedImage.src=URL.createObjectURL(blob);

const fd=new FormData();
fd.append("image",blob);

fetch("http://localhost:5001/recognize",{method:"POST",body:fd})
.then(r=>r.json())
.then(face=>{
openPopup();
resetPopup();

if (!face.roll) {
    faceMatched = false;
    errorMsg.style.display = "block";
    confirmBtn.style.display = "none";
    purposeBlock.style.display = "none";
    statusRow.style.display = "none";
    return;
}

faceMatched = true;
currentRoll = face.roll;
statusRow.style.display = "block";
pStatus.innerText = "Pending";

fetch(`get_student.php?roll=${currentRoll}`)
.then(r=>r.json())
.then(s=>{
pName.innerText=s.name;
pRoll.innerText=s.roll;
pHostel.innerText=s.hostel;
pRoom.innerText=s.room;
pPhone.innerText=s.phone;
});
});
},"image/jpeg");
}

function confirmEntry(){
    if(!faceMatched) return;

    const purpose = pPurpose.value;
    if(!purpose){
        alert("Select purpose");
        return;
    }

    const fd = new FormData();
    fd.append("roll", currentRoll);
    fd.append("purpose", purpose);

    fetch("confirm_entry.php", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(res => {

        /* ❌ DUPLICATE / ERROR */
        if(res.status !== "success"){
            alert(res.message || "Duplicate scan detected");
            return; // do NOTHING else
        }

        /* ✅ SUCCESS */
        alert(res.action + " confirmed");

        // 🔥 THIS IS THE KEY LINE
        pStatus.innerText = res.action;

        // close popup AFTER status update
        setTimeout(() => {
            closePopup();
        }, 300);

    })
    .catch(() => {
        alert("Server error");
    });
}


function resetPopup(){
errorMsg.style.display="none";
confirmBtn.style.display="inline-block";
purposeBlock.style.display="block";
statusRow.style.display="block";

pName.innerText="";
pRoll.innerText="";
pHostel.innerText="";
pRoom.innerText="";
pPhone.innerText="";
pStatus.innerText="Pending";
}

function openPopup(){
overlay.style.display="block";
studentPopup.style.display="block";
}

function closePopup(){
overlay.style.display="none";
studentPopup.style.display="none";
}
</script>

</body>
</html>
