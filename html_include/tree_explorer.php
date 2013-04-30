<?php 

/* Il seguente codice esplora la cartella utente, ipotizzando che la sessione sia già stata inizializzata
 * e quindi le variabili di sessione siano disponibili.
 */


function tree_xml_decode($xml, $path) {

	global $output;
	global $def_function;
 
	foreach ($xml->directory as $directory) {
		$randID = rand(1000,9999);
		$idname = 'id' . preg_replace("/[^A-Za-z0-9]/", '', $directory['name'] . $randID); // Aggiunto per evitare problemi con le cartelle con stesso nome
		$output .= '<li>
						<h5>
							<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#' . $idname . '"> 
								<i class="icon-folder-open icon-white" id="c' . $idname . '"></i>
							</button>
							' . $directory['name'] . '
						</h5>
						<div id="' . $idname . '" class="collapse" style="border: 1px solid #e5e5e5">
							<ul class="nav nav-list">';
							
							tree_xml_decode($directory, $path . $directory['name'] . '/');
		
		$output .= '		</ul>
						</div>
					</li>
					';
	}
	foreach ($xml->file as $file) {
		
		$href = '<a href="#" onclick="click_fun(this)">' . $path . $file['name'] . '</a>';
		
		$output .= '<li>
						<h5>
						<a onclick="Popup('."'".'catfile.php?file2cat='.$path.$file['name']."'".')" data-toggle="tooltip" title="Open and show file">  
						<i class="icon-file"></i></a> 
						<a onclick="Popup('."'".'download.php?file='.$path.$file['name']."'".')" data-toggle="tooltip" title="Download">  
						<i class="icon-download-alt"></i></a>
						<a onclick="Popup('."'".'download.php?file='.$path.$file['name']."'".')" data-toggle="tooltip" title="Put file in launch textbox">  
						<i class="icon-circle-arrow-right"></i></a>
						' . $file['name'] . '
						<small>Last Modified: ' . $file['time'] . ' - Size: ' . $file['size'] . 'B - Path: '. $href  .'</small></h5>
					</li>
					';                            
	}		
}
 
 
$tree = ssh2_exec2($_SESSION['username'],$_SESSION['password'], 'tree -h -D -X ' . $_SESSION['home']);

$treexml = simplexml_load_string($tree);

$output = '	<div class="well">
				<ul class="nav nav-list">';
					tree_xml_decode($treexml->directory, '');
$output .= '	</ul>
			</div>';

echo $output;

?>