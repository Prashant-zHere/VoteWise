<?php

if(isset($_GET['key']))
{
    include("../../include/conn/conn.php");
    $key = mysqli_real_escape_string($conn, $_GET['key']);
    if($key != "") 
        $query = "select * from candidates where id LIKE '%$key%' or name LIKE '%$key%' or email LIKE '%$key%'";
    else
        $query = "select * from candidates";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 0)
    {
        echo "<p style='text-align:center;font-size:18px;color:#555;'>No candidates found.</p>";
    }
    else
    {
        echo "<div class='table-container'>
                <table class='data-table'>
                    <thead>
                        <tr>
                            <th style='width:20px;text-align:left;'>S.No</th>
                            <th style='width:20px;text-align:left;'>Photo</th>
                            <th style='width:14%;text-align:left;'>ID</th>
                            <th style='width:14%;'>Name</th>
                            <th style='width:14%;'>Email</th>
                            <th style='width:10%;'>Gender</th>
                                <th style='width:10%;text-align:left;'>Status</th>
                                <th style='width:14%;text-align:left;'>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
        $sno = 1;
        while($row = mysqli_fetch_assoc($result))
        {
            // Get position title
            $pos_title = 'N/A';
            $posq = mysqli_query($conn, "SELECT title FROM positions WHERE id = '".$row['position_id']."'
            ");
            if($posq && $posr = mysqli_fetch_assoc($posq)) $pos_title = $posr['title'];

            echo "<tr>
                    <td style='width:20px;text-align:center;'>".$sno++."</td>
                    <td style='width:20px;text-align:center;'><img src='../include/photo/".htmlspecialchars($row['photo'])."' alt='Photo' style='width:48px;height:48px;border-radius:50%;object-fit:cover;'></td>
                    <td style='width:14%;text-align:left;'>".htmlspecialchars($row['id'])."</td>
                    <td style='width:14%;'>".htmlspecialchars($row['name'])."</td>
                    <td style='width:14%;'>".htmlspecialchars($row['email'])."</td>
                    <td style='width:10%;'>".htmlspecialchars($row['gender'])."</td>
                    <td style='width:10%;text-align:center;'><span class='status ".htmlspecialchars($row['status'])."'>".htmlspecialchars(ucfirst($row['status']))."</span></td>
                    <td class='action-col' style='width:14%;text-align:center;'>
                        <button type='button' class='btn-view' onclick='showProfileModal(
                            \"".htmlspecialchars($row['id'])."\",
                            \"".htmlspecialchars($row['photo'])."\",
                            `".htmlspecialchars($row['about'])."`,
                            \"".htmlspecialchars($row['status'])."\",
                            \"".htmlspecialchars($row['email'])."\",
                            \"".htmlspecialchars($row['gender'])."\",
                            \"".htmlspecialchars($pos_title)."\"
                        )'>View</button>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this candidate?\");'>
                            <input type='hidden' name='candidate_id' value='".htmlspecialchars($row['id'])."'>
                            <button type='submit' name='delete_candidate' class='btn-delete' title='Delete'>Delete</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</tbody>
                </table>
            </div>";

    }
}

///////////////////////////////////////////////////////////////////////

if(isset($_GET['status']))
{
    include("../../include/conn/conn.php");
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    if($status == "all" || $status == "") 
        $query = "select * from candidates";
    else
        $query = "select * from candidates where status='$status'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 0)
    {
        echo "<p style='text-align:center;font-size:18px;color:#555;'>No candidates found.</p>";
    }
    else
    {
        echo "<div class='table-container'>
                <table class='data-table'>
                    <thead>
                        <tr>
                            <th style='width:20px;text-align:left;'>S.No</th>
                            <th style='width:20px;text-align:left;'>Photo</th>
                            <th style='width:14%;text-align:left;'>ID</th>
                            <th style='width:14%;'>Name</th>
                            <th style='width:14%;'>Email</th>
                            <th style='width:10%;'>Gender</th>
                                <th style='width:10%;text-align:left;'>Status</th>
                                <th style='width:14%;text-align:left;'>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
        $sno = 1;
        while($row = mysqli_fetch_assoc($result))
        {
            $pos_title = 'N/A';
            $posq = mysqli_query($conn, "SELECT title FROM positions WHERE id = '".$row['position_id']."'
            ");
            if($posq && $posr = mysqli_fetch_assoc($posq)) $pos_title = $posr['title'];

            echo "<tr>
                    <td style='width:20px;text-align:center;'>".$sno++."</td>
                    <td style='width:20px;text-align:center;'><img src='../include/photo/".htmlspecialchars($row['photo'])."' alt='Photo' style='width:48px;height:48px;border-radius:50%;object-fit:cover;'></td>
                    <td style='width:14%;text-align:left;'>".htmlspecialchars($row['id'])."</td>
                    <td style='width:14%;'>".htmlspecialchars($row['name'])."</td>
                    <td style='width:14%;'>".htmlspecialchars($row['email'])."</td>
                    <td style='width:10%;'>".htmlspecialchars($row['gender'])."</td>
                    <td style='width:10%;text-align:center;'><span class='status ".htmlspecialchars($row['status'])."'>".htmlspecialchars(ucfirst($row['status']))."</span></td>
                    <td class='action-col' style='width:14%;text-align:center;'>
                        <button type='button' class='btn-view' onclick='showProfileModal(
                            \"".htmlspecialchars($row['id'])."\",
                            \"".htmlspecialchars($row['photo'])."\",
                            `".htmlspecialchars($row['about'])."`,
                            \"".htmlspecialchars($row['status'])."\",
                            \"".htmlspecialchars($row['email'])."\",
                            \"".htmlspecialchars($row['gender'])."\",
                            \"".htmlspecialchars($pos_title)."\"
                        )'>View</button>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this candidate?\");'>
                            <input type='hidden' name='candidate_id' value='".htmlspecialchars($row['id'])."'>
                            <button type='submit' name='delete_candidate' class='btn-delete' title='Delete'>Delete</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</tbody>
                </table>
            </div>";
    }
}

if(isset($_GET['gender']))
{
    include("../../include/conn/conn.php");
    $gender = mysqli_real_escape_string($conn, $_GET['gender']);

    echo $gender."Gender";

    if($gender == "all" || $gender == "") 
        $query = "select * from candidates";
    else
        $query = "select * from candidates where gender='$gender'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 0)
    {
        echo "<p style='text-align:center;font-size:18px;color:#555;'>No candidates found.</p>";
    }
    else
    {
        echo "<div class='table-container'>
                <table class='data-table'>
                    <thead>
                        <tr>
                            <th style='width:20px;text-align:left;'>S.No</th>
                            <th style='width:20px;text-align:left;'>Photo</th>
                            <th style='width:14%;text-align:left;'>ID</th>
                            <th style='width:14%;'>Name</th>
                            <th style='width:14%;'>Email</th>
                            <th style='width:10%;'>Gender</th>
                                <th style='width:10%;text-align:left;'>Status</th>
                                <th style='width:14%;text-align:left;'>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
        $sno = 1;
        while($row = mysqli_fetch_assoc($result))
        {
            $pos_title = 'N/A';
            $posq = mysqli_query($conn, "SELECT title FROM positions WHERE id = '".$row['position_id']."'
            ");
            if($posq && $posr = mysqli_fetch_assoc($posq)) $pos_title = $posr['title'];

            echo "<tr>
                    <td style='width:20px;text-align:center;'>".$sno++."</td>
                    <td style='width:20px;text-align:center;'><img src='../include/photo/".htmlspecialchars($row['photo'])."' alt='Photo' style='width:48px;height:48px;border-radius:50%;object-fit:cover;'></td>
                    <td style='width:14%;text-align:left;'>".htmlspecialchars($row['id'])."</td>
                    <td style='width:14%;'>".htmlspecialchars($row['name'])."</td>
                    <td style='width:14%;'>".htmlspecialchars($row['email'])."</td>
                    <td style='width:10%;'>".htmlspecialchars($row['gender'])."</td>
                    <td style='width:10%;text-align:center;'><span class='status ".
                        htmlspecialchars($row['status'])."'>".htmlspecialchars(ucfirst($row['status']))."</span></td>
                    <td class='action-col' style='width:14%;text-align:center;'>
                        <button type='button' class='btn-view' onclick='showProfileModal(
                            \"".htmlspecialchars($row['id'])."\",
                            \"".htmlspecialchars($row['photo'])."\",
                            `".htmlspecialchars($row['about'])."`,
                            \"".htmlspecialchars($row['status'])."\",
                            \"".htmlspecialchars($row['email'])."\",
                            \"".htmlspecialchars($row['gender'])."\",
                            \"".htmlspecialchars($pos_title)."\"
                        )'>View</button>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this candidate?\");'>
                            <input type='hidden' name='candidate_id' value='".htmlspecialchars($row['id'])."'>
                            <button type='submit' name='delete_candidate' class='btn-delete' title='Delete'>Delete</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</tbody>
                </table>
            </div>";
    }
}

?>








<!-- <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:20px;text-align:left;">S.No</th>
                                <th style="width:20px;text-align:left;">Photo</th>
                                <th style="width:14%;text-align:left;">ID</th>
                                <th style="width:14%;">Name</th>
                                <th style="width:14%;">Email</th>
                                <th style="width:10%;">Gender</th>
                                <th style="width:10%;text-align:left;">Status</th>
                                <th style="width:14%;text-align:left;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "select * from candidates";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result) == 0)
                                {
                                    echo "<tr class='no-results'><td colspan='9'>No candidates found.</td></tr>";
                                }
                                else
                                {
                                    $sno = 1;
                                    while($row = mysqli_fetch_assoc($result))
                                    {
                                        // Get position title
                                        $pos_title = 'N/A';
                                        $posq = mysqli_query($conn, "SELECT title FROM positions WHERE id = '".$row['position_id']."'
                                        ");
                                        if($posq && $posr = mysqli_fetch_assoc($posq)) $pos_title = $posr['title'];

                                        echo "<tr>
                                                <td style='width:20px;text-align:center;'>".$sno++."</td>
                                                <td style='width:20px;text-align:center;'><img src='../include/photo/".htmlspecialchars($row['photo'])."' alt='Photo' style='width:48px;height:48px;border-radius:50%;object-fit:cover;'></td>
                                                <td style='width:14%;text-align:left;'>".htmlspecialchars($row['id'])."</td>
                                                <td style='width:14%;'>".htmlspecialchars($row['name'])."</td>
                                                <td style='width:14%;'>".htmlspecialchars($row['email'])."</td>
                                                <td style='width:10%;'>".htmlspecialchars($row['gender'])."</td>
                                                <td style='width:10%;text-align:center;'><span class='status ".htmlspecialchars($row['status'])."'>".htmlspecialchars(ucfirst($row['status']))."</span></td>
                                                <td class='action-col' style='width:14%;text-align:center;'>
                                                    <button type='button' class='btn-view' onclick='showProfileModal(
                                                        \"".htmlspecialchars($row['id'])."\",
                                                        \"".htmlspecialchars($row['photo'])."\",
                                                        `".htmlspecialchars($row['about'])."`,
                                                        \"".htmlspecialchars($row['status'])."\",
                                                        \"".htmlspecialchars($row['email'])."\",
                                                        \"".htmlspecialchars($row['gender'])."\",
                                                        \"".htmlspecialchars($pos_title)."\"
                                                    )'>View</button>
                                                    <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this candidate?\");'>
                                                        <input type='hidden' name='candidate_id' value='".htmlspecialchars($row['id'])."'>
                                                        <button type='submit' name='delete_candidate' class='btn-delete' title='Delete'>Delete</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div> -->