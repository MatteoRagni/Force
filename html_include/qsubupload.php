<div id="drop_zone" onclick="$('#input_file').click()">	
	<h1 align="center">Uploader <small>drag and drop</small></h1>
	<h4>or click to open dialog</h4>
	<p>Left here to upload in your $HOME directory<br />
	<small>$HOME = <? echo $_SESSION['home']; ?></small></p>
</div>
<form class="form-actions"  method="post" action="upload_scp.php" enctype="multipart/form-data">
	<input id="input_file" style="display:none;" type="file" multiple="" name="filesToUpload[]"/>
	<input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="demo">
	<div id="list_file"></div>
	<button type="submit" class="btn btn-primary btn-large" value="Send"><i class="icon-white icon-file"></i> Upload files</button>
</form>



<script>
$('#input_file').change( function() {
		//get the input and UL list
	var input = document.getElementById('input_file');
	var list = document.getElementById('list_file');
	var output = [];
		
	for (var i = 0, f; f = input.files[i]; i++) {
		output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
					f.size, ' bytes, last modified: ',
					f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
					'</li>');
	}
	document.getElementById('list_file').innerHTML = '<ul>' + output.join('') + '</ul>';
});

// source: Eric Bidelman @ html5rocks
function handleFileSelect(evt) {
	evt.stopPropagation();
	evt.preventDefault();

	var files = evt.dataTransfer.files; // FileList object.

	// files is a FileList of File objects. List some properties.
	var output = [];
	var input = document.getElementById('input_file');
	input.files = files;
	for (var i = 0, f; f = files[i]; i++) {
	  output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
				  f.size, ' bytes, last modified: ',
				  f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
				  '</li>');
	}
	document.getElementById('list_file').innerHTML = '<ul>' + output.join('') + '</ul>';
}

function handleDragOver(evt) {
	evt.stopPropagation();
	evt.preventDefault();
	evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
}

// Setup the dnd listeners.
var dropZone = document.getElementById('drop_zone');
dropZone.addEventListener('dragover', handleDragOver, false);
dropZone.addEventListener('drop', handleFileSelect, false);

 
</script>

<!-- Provo a scrivere un pezzo di codice chhe mi permetta di visualizzare il caricamento sulla pagina-->

