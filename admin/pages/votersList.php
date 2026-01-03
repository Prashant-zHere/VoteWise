<?php
if(isset($_GET['key']))
{
    include_once '../../include/conn/conn.php';
    $key = isset($_GET['key']) ? $_GET['key'] : '';

    $key = mysqli_real_escape_string($conn, $key);
    if ($key != '')
        $query = "select * from voters where id like '%$key%' or name like '%$key%' or email LIKE '%$key%' order by id asc";
    else
        $query = "select * from voters order by id asc";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0)
    {
        echo '<div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:8%;text-align:left;">S.No</th>
                            <th style="width:15%;text-align:center;">Profile Photo</th>
                            <th class="id-col" style="width:20%;text-align:center;">ID</th>
                            <th style="width:20%;">Name</th>
                            <th style="width:20%;">Email</th>
                            <th style="text-align:center;width:12%;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>';
        $sno = 0;
        while($row = mysqli_fetch_assoc($result))
        {
            echo "<tr>
                    <td style='text-align:center;width:8%;'>".++$sno."</td>
                    <td style='text-align:center;width:15%;'>
                        <img src='../include/photo/".$row['photo']."' alt='Voter Photo'>
                    </td>
                    <td class='id-col' style='text-align:center;width:20%;'>".$row['id']."</td>
                    <td class='voter-name' style='width:20%;'>".$row['name']."</td>
                    <td class='voter-email'  style='width:20%;'>".$row['email']."</td>  
                    <td class='action-col' style='text-align:center;width:12%;'>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this voter?\");'>
                            <input type='hidden' name='voter_id' value='".$row['id']."'>
                            <button type='submit' name='delete_voter' class='btn-delete' title='Delete'> Delete</button>
                        </form>
                    </td>     
                </tr>";
        }
        echo '      </tbody>
                </table>
            </div>';
    }
    else
    {
        echo "<div style='text-align:center;padding:20px 0;'>No voters found.</div>";
    }
}

?>
        

