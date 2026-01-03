function filterCandidates() {
    var xml = new XMLHttpRequest();
    var key = document.getElementById("candidateSearch").value;
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById("display").innerHTML = xml.responseText;
        }
    };
    xml.open("GET", "./pages/candidateList.php?key="+key);
    xml.send();
}

////////////////////////////////////////////////////////////

function filterByStatus()
{
    var status = document.getElementById("status").value;
    console.log(status);
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById("display").innerHTML = xml.responseText;
        }
    };
    // document.querySelector('#gender').selectedIndex = 0;
    xml.open("GET", "./pages/candidateList.php?status="+status);
    xml.send();
}


///////////////////////////////////////////////////////////
function filterByGender()
{
    // var gender = document.getElementById("gender").value;
    var gender = "Male";
    console.log(gender + "ashugrf");
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById("display").innerHTML = xml.responseText;
        }
    };
    // document.querySelector('#status').selectedIndex = 1;
    // xml.open("GET", "./pages/candidateList.php?gender="+gender);
    xml.open("GET", "./pages/candidateList.php?gender=Male");
    xml.send();
}



/////////////////////////////////////////////
function showProfileModal(id, photo, about, status, email, gender, position) {
    let actions = '';
    if(status === 'pending') {
        actions = `
            <form method="POST" style="display:inline;">
                <input type="hidden" name="candidate_id" value="${id}">
                <button type="submit" name="approve_candidate" class="btn-approve">Approve</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="candidate_id" value="${id}">
                <button type="submit" name="reject_candidate" class="btn-reject">Reject</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="candidate_id" value="${id}">
                <button type="submit" name="delete_candidate" class="btn-delete">Delete</button>
            </form>
        `;
    } else if(status === 'rejected') {
        actions = `
            <form method="POST" style="display:inline;">
                <input type="hidden" name="candidate_id" value="${id}">
                <button type="submit" name="delete_candidate" class="btn-delete">Delete</button>
            </form>
        `;
    } else if(status === 'approved') {
        actions = `
            <form method="POST" style="display:inline;">
                <input type="hidden" name="candidate_id" value="${id}">
                <button type="submit" name="reject_candidate" class="btn-reject">Reject</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="candidate_id" value="${id}">
                <button type="submit" name="delete_candidate" class="btn-delete">Delete</button>
            </form>
        `;
    }
    document.getElementById('modalProfileContent').innerHTML = `
        <div style="text-align:center;">
            <img src="../include/photo/${photo}" alt="Photo" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:12px;">
            <h3 style="margin:8px 0 4px 0;">${id}</h3>
            <div style="margin-bottom:8px;"><b>Status:</b> <span class="status ${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></div>
        </div>
        <div style="margin-bottom:8px;"><b>Email:</b> ${email}</div>
        <div style="margin-bottom:8px;"><b>Gender:</b> ${gender}</div>
        <div style="margin-bottom:8px;"><b>Position Applied For:</b> ${position}</div>
        <div style="margin-bottom:12px;"><b>About:</b><br>${about}</div>
        <div style="text-align:center;margin-top:16px;">${actions}</div>
    `;
    document.getElementById('profileModal').style.display = 'flex';
}
function closeProfileModal() {
    document.getElementById('profileModal').style.display = 'none';
}

// function sort()
// {
//     var status = document.getElementById("status").value;
//     console.log(status);
    
//     if(status != "")
//     {
//         var xml = new XMLHttpRequest();
//         xml.onreadystatechange = function(){
//             if(xml.status == 200 && xml.readyState == 4)
//                 document.getElementById("complaint").innerHTML = xml.responseText;
//         }

//         document.querySelector('#category').selectedIndex = 0;
//         xml.open("GET","../support/complaintList.php?status="+status);
//         xml.send();

//     }

// }

// function category()
// {
//     // console.log("aisefuifqiuweuf");
//     var category = document.getElementById("category").value;
//     console.log(category);
    
//     if(category != "")
//     {
//         var xml = new XMLHttpRequest();
//         xml.onreadystatechange = function(){
//             if(xml.status == 200 && xml.readyState == 4)
//                 document.getElementById("complaint").innerHTML = xml.responseText;
//         }

//         document.querySelector('#status').selectedIndex = 0;
//         xml.open("GET","../support/complaintList.php?category="+category);
//         xml.send();
//     }

// }

// window.onload = function() {
//     // var select = document.querySelector('#status');
//     // select.value = 'all';
//     sort();
//     // document.querySelector('#status').selectedIndex = 1;
// };

// function sortbydate()
// {
//     var start_date = document.getElementById("start_date").value;
//     var end_date = document.getElementById("end_date").value;

//     console.log(start_date+"   "+end_date);

//     if(start_date == "" && end_date == "")
//         return;

//     var xml = new XMLHttpRequest();
//         xml.onreadystatechange = function(){
//             if(xml.status == 200 && xml.readyState == 4)
//                 document.getElementById("complaint").innerHTML = xml.responseText;
//         }

//     if(start_date != "" && end_date != "")    
//         xml.open("GET","../support/complaintList.php?start_date="+start_date+"&end_date="+end_date);
//     else if(start_date == "" && end_date != "")
//         xml.open("GET","../support/complaintList.php?end_date="+end_date);
//     else if(start_date != "" && end_date == "")
//         xml.open("GET","../support/complaintList.php?start_date="+start_date);

//         xml.send();

// }
