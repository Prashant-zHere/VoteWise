<?php

if(isset($_GET['key']))
{
    include_once '../../include/conn/conn.php';
    $key = isset($_GET['key']) ? $_GET['key'] : '';
    $key = mysqli_real_escape_string($conn, $key);
    if ($key != '')
        $query = "select * from positions where title like '%$key%' order by id asc";
    else
        $query = "select * from positions order by id asc";

    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0)
    {
        echo '<div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:18%;">Title</th>
                            <th style="width:60%;">Description</th>
                            <th style="text-align:center;width:12%;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>';
        while($row = mysqli_fetch_assoc($result))
        {
            echo "<tr>
                    <td class='title-col'>".$row['title']."</td>
                    <td class='desc-col'>".$row['description']."</td>
                    <td class='action-col'>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this position?\");'>
                            <input type='hidden' name='position_id' value='".$row['id']."'>
                            <button type='submit' name='delete_position' class='btn-delete' title='Delete'> Delete </button>
                        </form>
                    </td>
                </tr>";
        }
    }
    else
    {
        echo "<tr><td colspan='3' style='text-align:center;'>No positions found.</td></tr>";
    }    
    echo '</tbody>
        </table>
    </div>';
}

?>


<!-- 
<div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:18%;">Title</th>
                        <th style="width:60%;">Description</th>
                        <th style="text-align:center;width:12%;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "select * from positions";
                        $result = mysqli_query($conn, $query);
                        if(mysqli_num_rows($result) > 0)
                        {
                            while($row = mysqli_fetch_assoc($result))
                            {
                                echo "<tr>
                                        <td class='title-col'>".$row['title']."</td>
                                        <td class='desc-col'>".$row['description']."</td>
                                        <td class='action-col'>
                                            <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this position?\");'>
                                                <input type='hidden' name='position_id' value='".$row['id']."'>
                                                <button type='submit' name='delete_position' class='btn-delete' title='Delete'> Delete </button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='3' style='text-align:center;'>No positions found.</td></tr>";
                        }
                    ?>

                </tbody>
            </table>
        </div> -->