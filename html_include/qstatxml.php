<?
function qstat_parser($string) {

	$xml = simplexml_load_string($string);
		
	$jobsdata_array = array();
	
	$i = 0;
	foreach($xml->Job as $job) {
		
		$j = array();
	
		$j['jid']       = (string) $job->Job_Id;
		$j['server']    = (string) $job->server;
		$j['name']      = (string) $job->Job_Name;
		$j['id']        = (int) str_replace('.'.$j['server'],'',$j['jid']);
		$j['owner']     = (string) str_replace('@'.$j['server'],'',$job->Job_Owner);
		$j['queue']     = (string) $job->queue;
		$j['errorfile'] = (string) $job->Error_Path;
		$j['outputfile']= (string) $job->Output_Path;
		$j['mail']      = (string) $job->Mail_Users;
		$j['nodes']     = (string) $job->Resource_List->nodes;
		$j['walltime']  = (string) $job->Resource_List->walltime;
		$j['script']    = (string) ($job->init_work_dir . $job->submit_args);
		
		$j['tstart']    = date('H:m:s \o\n d/m/Y',(int)$job->start_time);
		$j['runtime']   = (string) $job->resources_used->walltime; 
		$j['tend']      = date('H:m:s \o\n d/m/Y', (int) $job->comp_time);
		$j['mem']       = (string) $job->resources_used->mem;
		$j['vmem']      = (string) $job->resources_used->vmem;
		
		switch((string) $job->job_state) {
			case 'C': $j['state'] = 'Completed'; break;
			case 'E': $j['state'] = 'Exiting'; break;
			case 'H': $j['state'] = 'Held'; break;
			case 'Q': $j['state'] = 'Queued'; break;
			case 'R': $j['state'] = 'Running'; break;
			case 'T': $j['state'] = 'Transfered'; break;
			case 'W': $j['state'] = 'Waiting'; break;
			default:  $j['state'] = 'Unknown'; break;	
		}
		//$j['state'] = (string) $job->job_state;
		
		if(preg_match("/b/i",$job->Mail_Points)) { $j['mail_begin'] = true; } else {  $j['mail_begin'] = false; }
		if(preg_match("/e/i",$job->Mail_Points)) { $j['mail_end'] = true; } else {  $j['mail_end'] = false; }
		if(preg_match("/a/i",$job->Mail_Points)) { $j['mail_abort'] = true; } else {  $j['mail_abort'] = false; }
		
		$j['node_list'] = str_replace('+',"<br/>", preg_replace("/[[:cntrl:]]/", '',(string) $job->exec_host));
		if( $j['owner'] == $_SESSION['username'] ) { $j['owned'] = true; } else { $j['owned'] = false; }
		
		// Advances: calcola la percentuale di tempo rimasto al walltime
		//preg_match_all('/[0-9]{1,4}/','01:02:03',$wall_int);
		//$wall_int = (60*60)*$wall_int[0][0] + 60*$wall_int[0][1] + $wall_int[0][2]; 
		$wall_int = explode(':',$j['walltime']);
		$wall_int = (60*60)*$wall_int[0] + 60*$wall_int[1] + $wall_int[2]; 
		$j['tpercent'] = round((time()-(int)$job->start_time)/$wall_int*100);
		$j['tpercent'] = $j['tpercent'] > 100? 100: $j['tpercent'];
				
		$jobsdata_array[$i++] = $j;
	}
	
	return $jobsdata_array;
}

function make_modal($job) {
	
	$output = "";
	return $output;
}

function make_job_table($j) {

	$output = "";
	
	switch ($j['state']) {
		case 'Completed': $badge = 'badge-success'; break;
		case 'Queued':    $badge = '';              break;
		case 'Running':   $badge = 'badge-info';    break;
		case 'Exiting':   $badge = 'badge-warning'; break;
		default:          $badge = 'badge-inverse'; break;
	}
	
	$output .= '<div class="well well-small">';
	$output .= '	<table width="100%">';
	$output .= '		<tr>';
	$output .= '			<td width="10%">';
	$output .= '				<span class="badge '.$badge.'">'.$j['state'].'</span>';
	$output .= '		</td>';
	$output .= '		<td width="10%">';
if ($j['state'] === 'Running') {
	$output .= '			<div class="progress jobwall barlist">';
	$output .= '				<div class="bar" style="width: '.$j['tpercent'].'%;"></div>';
	$output .= '			</div>';
}
	$output .= '		</td>';
	$output .= '		<td width="10%">';
	$output .= '			<a href="#" data-toggle="modal" data-target="#mod'.$j['name'].$j['id'].'">';
	$output .= '				<h5 class="text-info">'.$j['id'].'</h5>';
	$output .= '			</a>';
	$output .= '		</td>';
	$output .= '		<td width="30%">';
	$output .= '			<a href="#" data-toggle="modal" data-target="#mod'.$j['name'].$j['id'].'">';
	$output .= '				<h5>'.$j['name'].'</h5> ';
	$output .= '			</a>';
	$output .= '		</td>';
	$output .= '		<td class="muted" width="30%">'.$j['owner'].'</td>';
	$output .= '		<td align="right" width="10%">';
if ($j['owned'] === true && ($j['state'] === 'Queued' || $j['state'] === 'Running')) {
	$output .= '			<a href="job_delete.php?jid='.$j['id'].'">';
	$output .= '				<span class="badge badge-important">Delete</span>';
	$output .= '			</a>';
}
	$output .= '		</td>';
	$output .= '	</tr>';
	$output .= '	</table>';
	$output .= '</div>';

$output .= '
<div id="mod'.$j['name'].$j['id'].'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">
	<span class="badge '.$badge.' modalbadge">Completed</span>
	   '.$j['name'].' ('.$j['id'].')
	</h3>
  </div>
  <div class="modal-body">
  <table class="table">
    <thead>
		<tr>
			<th colspan="2">Timing</th>
		</tr>
	</thead>
		<tbody>
			<tr>
				<td class="tdelem">Time start</td>
				<td>'.$j['tstart'].'</td>
			</tr><tr>
				<td class="tdelem">Walltime</td>
				<td>'.$j['walltime'].'</td>
			</tr><tr>';
	if( $j['state'] === 'Completed' ) {
	$output .=' <td class="tdelem">Runtime</td>
				<td>'.$j['runtime'].'</td>
			</tr><tr>
				<td class="tdelem">Time end</td>
				<td>'.$j['tend'].'</td>
			</tr>';
	}
	if( $j['state'] !== 'Completed') {
	$output .='<tr> 
				<td class="tdelem">To end</td>
				<td>'.$j['tpercent'].'% 
					<div class="progress barmodal">
						<div class="bar" style="width: '.$j['tpercent'].'%;"></div>
					</div>
				</td>
			</tr>';
	}
$output.='		</tbody>
	</table>
	<table class="table">
	<thead>
		<tr>
			<th colspan="2">Files</th>
		</tr>
	</thead>
		<tbody>
			<tr>';
if( $j['owned'] === true ) {
	if( $j['state'] === 'Completed' ) {
	$output .='				<td class="tdelem">Output path</td>
					<td>'.$j['outputfile'].'</td>
				</tr><tr>
					<td class="tdelem">Error path</td>
					<td>'.$j['errorfile'].'</td>
				</tr><tr>'; 
	}
$output .='				<td class="tdelem">Script path</td>
				<td>'.$j['script'].'</td>'; 
} else { $output .= '<td class="tdelem"></td><td>Only owner can see this</td>'; }
$output .='			</tr>
		</tbody>
	</table>
	<table class="table">
	<thead>
		<tr>
			<th colspan="2">Mail</th>
		</tr>
	</thead>
		<tbody>
			<tr>
				<td class="tdelem">Owner</td>
				<td>'.$j['owner'].'</td>
			</tr><tr>
				<td class="tdelem">Queue</td>
				<td>'.$j['queue'].'</td>';
if( $j['owned'] === true ) {
$output .='			</tr><tr>
				<td class="tdelem">Mail</td>
				<td>'.$j['mail'].'</td>
			</tr>
			</tr><tr>
				<td class="tdelem">Mail Options</td>
				<td>
					<div class="badge badge-inverse">Begin</div>
					<div class="badge badge-inverse">End</div>
					<div class="badge badge-inverse">Inverse</div>
				</td>'; 
}
$output.='			</tr>
		</tbody>
	</table>
	<table class="table">
	<thead>
		<tr>
			<th colspan="2">Resources</th>
		</tr>
	</thead>
		<tbody>
			<tr>
				<td class="tdelem">Server</td>
				<td>'.$j['server'].'</td>
			</tr><tr>
				<td class="tdelem">Nodes</td>
				<td>'.$j['nodes'].'</td>
			</tr>';
if( $j['state'] === 'Completed' ) {
	$output .='			
			<tr>
				<td class="tdelem">Mem/Vmem</td>
				<td>'.$j['mem'].' / '.$j['vmem'].'</td>
			</tr>';
}
$output .= '			<tr>
				<td class="tdelem">Node List</td>
				<td>
					<a href="#" style="border:1px dash #cfcfcf;" data-toggle="collapse" data-target="#coll'.$j['name'].$j['id'].'"
					   onclick="
					   if (innerHTML == '."'Show'".') { innerHTML = '."'Collapse'".'; }
					   else { innerHTML = '."'Show'".'; }
					">Show</a>
					<div id="coll'.$j['name'].$j['id'].'" class="collapse">
					<div class="well well-small">
						'.$j['node_list'].'
					</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
	if( $j['owned'] === true && ($j['state'] === 'Queued' || $j['state'] === 'Running')) {
	$output .='<a class="btn btn-danger" href="job_delete.php?jid='.$j['id'].'">Delete Job</a>';
	}
$output .= '  </div>
</div>';

	
	return $output;
	
}	

?>
<!--<div class="well well-small">-->
	<table width="100%">
		<tr>
			<td width="10%"><span class="badge">Status</span> </td>
			<td width="10%"></td>
			<td width="10%"><h5>JID</h5></td>
			<td width="30%"><h5>Job name</td>
			<td class="muted" width="30%"><h5>Owner</h5></td>
			<td align="right" width="10%"></td>
		</tr>
	</table>
<!--</div>-->
<hr/>

<?
// Debug options :: qstat has to be modified before production
if($debug) {
	$qstat_cmd = 'cat $HOME/qstat.xml';
} else {
	$qsta_cmd = 'qstat -X';
}

$qstat_xml = ssh2_exec2($_SESSION['username'], $_SESSION['password'], $qstat_cmd);

$jobout = "";
$joblist = qstat_parser($qstat_xml);
foreach($joblist as $job) {
	$jobout .= make_job_table($job);
}
echo $jobout;

?>