<?php
require_once '../../tcpdf/tcpdf.php';
// require_once(__DIR__ . '../../include/conn/conn.php');
$conn = mysqli_connect("localhost","root","","online_voting");

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Voting System');
$pdf->SetTitle('Election Results');
// $pdf->SetHeaderData('', 0, 'Election Results', '', array(0,64,255), array(0,64,128));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 18, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();

$title = 'Election Results';
$html = '<h2 align="center" style="color:#1976d2;">' . $title . '</h2>';
// $html .= '<h4 align="center">Tally Result</h4>';

$positions_query = "select * from positions ORDER BY id ASC";
$positions_result = mysqli_query($conn, $positions_query);
if ($positions_result && mysqli_num_rows($positions_result) > 0) {
    while ($position = mysqli_fetch_assoc($positions_result)) 
    {
        $position_id = $position['id'];
        $html .= '<h3 style="color:#185a9d;">' . htmlspecialchars($position['title']) . '</h3>';
        $html .= '<div style="margin-bottom:8px;font-size:13px;color:#444;">' . htmlspecialchars($position['description']) . '</div>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0" width="100%">';
        $html .= '<thead><tr style="background:#f2f6fc;">'
            . '<th width="8%">S.No</th>'
            . '<th width="22%">Name</th>'
            . '<th width="52%">About</th>'
            . '<th width="18%">Votes</th>'
            . '</tr></thead><tbody>';
        $candidates_query = "select c.id, c.name, c.about from candidates c where c.position_id = $position_id and c.status = 'approved' ORDER BY c.name ASC";
        $candidates_result = mysqli_query($conn, $candidates_query);
        if ($candidates_result && mysqli_num_rows($candidates_result) > 0) 
        {
            $sno = 1;
            while ($candidate = mysqli_fetch_assoc($candidates_result)) 
            {
                $vote_count_query = "select COUNT(*) as vote_count from votes where candidate_id = '" . $candidate['id'] . "'";
                $vote_count_result = mysqli_query($conn, $vote_count_query);
                $vote_count = 0;
                if ($vote_count_result) 
                {
                    $vote_row = mysqli_fetch_assoc($vote_count_result);
                    $vote_count = $vote_row['vote_count'];
                }
                $html .= '<tr>'
                    . '<td width="8%">' . $sno++ . '</td>'
                    . '<td width="22%">' . htmlspecialchars($candidate['name']) . '</td>'
                    . '<td width="52%">' . htmlspecialchars($candidate['about']) . '</td>'
                    . '<td width="18%">' . $vote_count . '</td>'
                    . '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="4" align="center">No candidates for this position.</td></tr>';
        }
        $html .= '</tbody></table><br>';
    }
}
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('election_results.pdf', 'I');