<form name="qsub_create" action="" type="POST">

<!-- Struttura e elementi da implementare

* link al qsub man

### OPZIONI GENERALI
* -N nome del job che viene sottomesso
* opzione -V esporta tutte le variabili d'ambiente
* -f job fault tollerant, ovvero sopravvive alla perdita di alcuni mom che non siano quello superiore
* -r definisce se il job può essere rieseguito


### RISORSE
* -l specificare quali risorse usare
* -q definisce la queue da utilizzare
* -p definisce la priorità del job è un valore numerico tra -1024 e +1023
* -W definizione di attributi aggiuntivi, nel nostro caso 
* -a definisce l'ora e il momento in cui il job deve essere eseguito


### OUTPUT
* -j definisce se error e output devono essere merged
* -k definisce quali se gli stream di input e output devono essere mantenuti
* -o definisce la directory alla quale forniire gli stream di output

### MAILING
* -m condizioni alle quali viene inviata una mail
* -M indirizzo al quale viene inviato la mail

### TEXT to insert script
-->

<!-- PHP Functions to create elements -->
<?php

function create_list($arrayin, $name) 
{
	$output = "";
	$output = "<select name=\"" . $name . "\" id=\"" . $name . "\"  onchange=\"compose();\">";
	
	for ($i = 0; $i < count($arrayin); ++$i) {
		$output .= "\n" . "<option>" . $arrayin[$i] . "</option>";
	}
	$output .= "\n</select>";
	return $output;
}

?>

<script>

 // Fa il check dell'input
function getJobname() {
	var jobname = $('input[name="jobname"]').val();
	
	if (check_string(jobname)) {
		$('#jobname_ee').popover('hide');
		return jobname;
	} else { 
		$('#jobname_ee').popover('show');
		return "";
	}
}	
	
function compose() {
		
		var jobname = getJobname();
		var exportEnv = $('input[name="exportEnv"]').val();
		var HFaultToll = $('input[name="HFaultToll"]').val();
		var RerunJob = $('input[name="RerunJob"]').val();
/*M*/	var ResList_Nodes = $('input[name="ResList_Nodes"]').val();
/*M*/	var ResList_Cores = $('input[name="ResList_Cores"]').val();
/*M*/	var RequireGPUS = $('input[name="RequireGPUS"]').val();
/*M*/	var BatchSelection = $("#BatchSelection").val();
/*M*/	var priority = $('input[name="priority"]').val();
/*M*/	var ResList_HH = $('input[name="ResList_HH"]').val();
/*M*/	var ResList_MM = $('input[name="ResList_MM"]').val();
/*M*/	var ResList_SS = $('input[name="ResList_SS"]').val();
/*M*/	var Submission_HH = $('input[name="submission_HH"]').val();
/*M*/	var Submission_MM = $('input[name="submission_MM"]').val();
/*M*/	var output_path = $('input[name="output_path"]').val();
/*M*/	var RequireOutput = $('input[name="RequireOutput"]').prop("checked");
/*M*/	var RequireError = $('input[name="RequireError"]').prop("checked");
		var MergeOutput = $('input[name="MergeOutput"]').val();
/*M*/	var MailBegin = $('input[name="MailBegin"]').prop("checked");
/*M*/	var MailEnd = $('input[name="MailEnd"]').prop("checked");
/*M*/	var MailAbort = $('input[name="MailAbort"]').prop("checked");
/*M*/	var mail_addr = $('input[name="mail_addr"]').val();
		var bash_body = $("#bash_body").val();
		var rc = "\n";
					
		var output ="#!/bin/sh" + rc;
		
		output += ("### General Options ###" + rc);
					
		// Job name
		if (check_string(jobname)) {
			output += ('#PBS -N ' + jobname + rc);
		}
			
		// Export envvars
		if (exportEnv !== "") {
			output += (exportEnv + rc);
		}
		
		// High Fault tollerance
		if (HFaultToll !== "") {
			output += (HFaultToll + rc);
		}
		
		// Rerun Job
		if (RerunJob !== "") {
			output += (RerunJob + rc);
		}
		
		output += ("### Resource Handling ###" + rc);
		
		// Resource Nodes + Cores
		if (check_number(ResList_Nodes) && check_number(ResList_Cores)) {
			output += '#PBS -l ' + ResList_Nodes + ':' + ResList_Cores + rc;
		}
		
		// Require Gpus
		if (RequireGPUS === "true" ) { 
			output += ("#PBS -W \"-x GRES:gpu\"" + rc);
		}
		
		// Queue
		output += ('#PBS -q ' + BatchSelection + rc);
		
		// Priority
		if (priority === "") {}
		else if (Number(priority) > -1024 && Number(priority) < 1023) {
			output += ("#PBS -p " + priority + rc);
		}
		
		// Walltime <-- Questa parte deve essere implementata meglio
		var time;
		if (ResList_HH === "") { ResList_HH = "00"; }
		if (ResList_MM === "") { ResList_MM = "00"; }
		if (ResList_SS === "") { ResList_SS = "00"; }
		if ( Number(ResList_HH) >= 0 && Number(ResList_MM) >= 0 && Number(ResList_SS) >= 0) {
			if ( Number(ResList_HH) <= 99 && Number(ResList_MM) <= 59 && Number(ResList_SS) <= 59) {
				time = ResList_HH + ":" + ResList_MM + ":" + ResList_SS;
			}
		}
		if ( time !== "00:00:00" ) { 
			output += ('#PBS -l ' + time + rc);
		}
		
		// Submission Time <-- Questa parte deve essere implementata meglio
		time = "";
		if (Submission_HH !== "" && Submission_MM !== "") {
			if ( Number(Submission_HH) >= 0 && Number(Submission_HH) <= 24 && Number(Submission_MM) >= 0 && Number(Submission_MM) <= 59) {
				time = Submission_HH + ":" + Submission_MM;
				output += ('#PBS -a ' + time + rc);
			}
		}
		
		output += "### Output Stream Options ###" + rc;
		
		// Output path <-- check se la path esiste, se non esiste deve crearla, o per lo meno farlo notare al nostro caro amico che sottomette il job!
		if (output_path !== "") {
			output += ('#PBS -o ' + output_path + rc);
			output += ('#PBS -e ' + output_path + rc);
		}
		
		// Stream requested
		var temp = "#PBS -k ";
		if (RequireOutput && RequireError) { temp =""; }
		else if (!RequireOutput && RequireError) { temp += 'e'; }
		else if (RequireOutput && !RequireError) { temp += 'o'; }
		else if (!RequireOutput && !RequireError) { temp += 'n'; }
		if (temp !== "") { output += (temp + rc); }
		
		// Merge stream
		if (MergeOutput !== "") {
			output += MergeOutput + rc;
		}
		
		output += "### Mail Options ###" + rc;
		
		// Mail Requested
		temp = "#PBS -m ";
		if (!MailBegin && !MailEnd && !MailAbort) { temp =""; }
		else {
			if (MailBegin) { temp += 'b'; }
			if (MailEnd) { temp += 'e'; }
			if (MailAbort) { temp += 'a'; }
			temp += rc;
		}
		output += temp;
		
		// Mail address <-- forse dovrei fare un check sull'inserimento delle mail
		if (mail_addr !== "") {
			output += '#PBS -M ' + mail_addr + rc;
		}
		
		output += (rc + "### Bash script ###" + rc + bash_body + rc + "exit 0" + rc);

		$("#results_code").replaceWith('<pre id="results_code" class="input-bloc-level">' + output + '</pre>');
		stylePreview();
		$('#result_script').val(output);
		
}

// Check se nella stringa ci sono caratteri che non sono validi
// Caratteri validi: a-z, A-Z, 0-9, _, -
function check_string(input) {
	var re = new RegExp('[^a-zA-Z0-9-_]+');
	if (input.match(re)) { return false; }
	else { return true; }
}

// Check se nella stringa ci sono 2 numeri
function check_number(input) {
	var re = new RegExp('^[0-9]{1,2}$');
	if (input.match(re)) { return true; }
	else { return false; }
}

function stylePreview() {
    $("#results_code").addClass("prettyprint");
	$("#results_code").addClass("linenums");
    $("#results_code").html(prettyPrint($("#results_code").html()));
}	
</script>

<form id="form_compose">

<fieldset>
	<legend>General Options</legend> <!-- Tabella General Options -->
		<!-- Script name -->
		<div class="input-prepend input-append">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Script Name" data-content="In this field you can insert a name for your script. Remember that if there is already a script with the same name in your home folder, it'll be overwritten.<br /> Interface automatically adds .pbs extension" 
				data-html="true" data-container="body">Script Name</a>
				<input class="input-block-level input-xlarge" id="scriptname appendPrependInput" 
				type="text" name="scriptname" placeholder="Insert script name" />
				<span class="add-on">.pbs</span>
			</div>
		</div>
		<br/>
		
		<!-- Job name  -->
		<div class="input-prepend">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Job name | -N" data-content="<p>Declares a name for the job.  The name specified may be  up  to<br/> and  including  15  characters  in  length.  It must consist of printable, non white space characters with the first  character alphabetic.<br/></p><p> If  the  -N option  is not specified, the job name will be the base name of the job script file specified on the command line.</p>" 
				data-html="true" data-container="body">Job name</a>
			   	<a id="jobname_ee" href="#" data-toggle="popover" data-title="Error" 
				   data-content="Use only a-z, 0-9, - and _" data-trigger="manual" data-container="body">
				<input class="input-block-level input-xlarge" id="jobname prependInput" type="text" 
				name="jobname" placeholder="Insert job name" onkeypress="compose();"/>
				</a>
			</div>
		</div>
		<br/>
		
		<!-- Export shell variable -->
		<div>
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Export env variables  | -V" data-content="Declares that all environment variables in the  qsub  command's environment are to be exported to the batch job." data-html="true" 
				data-container="body">Export Env Variables</a>
			   <!-- Elemento nascosto -->
			   <input style="display:none" type="text" name="exportEnv" id="exportEnv" value="#PBS -V">
			   <!-- Elementi visibili -->
			   <div class="btn-group" data-toggle="buttons-radio">
					<button type="button" class="btn span2 active" id="exportEnvYes" 
					onclick="
						document.getElementById('exportEnv').setAttribute('value', '#PBS -V'); 
						compose();">Yes</button>
					<button type="button" class="btn span2" id="exportEnvNo" 
					onclick="
						document.getElementById('exportEnv').setAttribute('value', ''); 
						compose();">No</button>
			   </div>
			</div>
		</div>
		<br/>
		
		<!-- High Fault tollerance -->
		<div>
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="High fault tollerance | -f" data-content="Specifies that the job is fault  tolerant.  The  fault_tolerant attribute will be set to true, which indicates that the job can survive the loss of a mom other than the <em>mother superior</em> mom
               (the first node in the exec hosts)" data-html="true" 
			   data-container="body">HFault Tollerance</a>
			   <!-- Elemento nascosto -->
			   <input style="display:none" type="text" name="HFaultToll" id="HFaultToll" value="">
			   <!-- Elementi visibili -->
			   <div class="btn-group" data-toggle="buttons-radio">
					<button type="button" class="btn span2" id="HFaultTollYes" 
					onclick="
						document.getElementById('HFaultToll').setAttribute('value', '#PBS -f'); 
						compose();">Yes</button>
					<button type="button" class="btn span2 active" id="HFaultTollNo" 
					onclick="
						document.getElementById('HFaultToll').setAttribute('value', ''); 
						compose();">No</button>
			   </div>
			</div>
		</div>
		<br/>
		
		<!-- Rerunable Job -->
		<div>
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Rerunable Job | -r y|n" data-content="Declares whether the job is rerunable." 
				data-html="true" data-container="body">Rerunable Job</a>
			   <!-- Elemento nascosto -->
			   <input style="display:none" type="text" name="RerunJob" id="RerunJob" value="">
			   <!-- Elementi visibili -->
			   <div class="btn-group" data-toggle="buttons-radio">
					<button type="button" class="btn span2" id="RerunJobYes" 
					onclick="
						document.getElementById('RerunJob').setAttribute('value', ''); 
						compose();">Yes</button>
					<button type="button" class="btn span2" id="RerunJob" 
					onclick="
						document.getElementById('RerunJob').setAttribute('value', '#PBS -r n'); 
						compose();">No</button>
			   </div>
			</div>
		</div>
		<br/>
</fieldset>

<fieldset>
	<legend>Resource Handling</legend>
		<!-- Resource Selection -->
		<div class="input-prepend input-append">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Resource list | -l" data-content="<p>Defines the resources that are required by the job  and  establishes  a limit to the amount of resource that can be consumed.</p><p>If not set for a generally  available  resource,  such  as  CPU time,  the limit is infinite.</p>" data-html="true" data-container="body">Resource list</a>
				<input  class="input-block-level input-medium" id="ResList_Nodes appendPrependInput" 
						type="number" maxlegth="3" name="ResList_Nodes" placeholder="Nodes" 
						min="0" max="999" onkeypress="compose();" onchange="compose();"/>
				<span class="add-on">:</span>
				<input 	class="input-block-level input-medium" id="ResList_Cores appendPrependInput" 
						type="number" maxlength="1" name="ResList_Cores" placeholder="Cores per node" 
						min="0" max="4" onkeypress="compose();" onchange="compose();"/>
			</div>
		</div>
		<br/>
		
		<!-- Require Gpus -->
		<div>
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Require GPUs | -W '-x=GRES:gpu'" data-content="Specifies that the job requires nodes with nVidia/CUDA gpus. This attribute can be used only with MAUI scheduler, and you should remember that each gpus requires at least one cores on every node." data-html="true" data-container="body">Require GPUs</a>
			   <!-- Elemento nascosto -->
			   <input style="display:none" type="checkbox" name="RequireGPUS" id="RequireGPUS">
			   <!-- Elementi visibili -->
			   <div class="btn-group" data-toggle="buttons-radio">
					<button type="button" class="btn span2" id="RequireGPUSyes" onclick="
							if (document.getElementById('RequireGPUS').checked === false) {
								document.getElementById('RequireGPUS').checked = true; }
							compose();">Yes</button>
					<button type="button" class="btn span2 active" id="RequireGPUSno" onclick="
							if (document.getElementById('RequireGPUS').checked) {
								document.getElementById('RequireGPUS').checked = false; }
							compose();">No</button>
			   </div>
			</div>
		</div>
		<br/>
		
		<!-- Batch selection -->
		<div>
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Queue Selection | -q" data-content="<p>Defines  the  destination  of the job.  The destination names a queue, a server, or a queue at a server.</p><p> The qsub command will submit the script to the  server  defined by  the  destination argument.  If the destination is a routing queue, the job may be routed by the server to  a  new  destina        tion.</p><p>If the -q option is not specified, the qsub command will submit the script to the default server.</p><p>If the -q option is specified, it is in one  of  the  following three forms: <pre>queue<br/>@server<br/>queue@server</pre></p>If  the  destination argument names a queue and does not name a  server, the job will be submitted to the  named  queue  at  the default server." 
				data-html="true" data-container="body">Queue selection</a>
				<? echo create_list($world['queue_list'],"BatchSelection"); ?>
			</div>
		</div>
		<br/>	
		
		<!-- Priority -->
		<div class="input-prepend">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Priority | -p" data-content="Defines the priority of the job.  The priority argument must be a integer between -1024 and +1023 inclusive.  The default is no priority which is equivalent to a priority of zero." 
				data-html="true" data-container="body">Priority</a>
				<input class="input-block-level input-xlarge" id="prependInput" type="number" 
				name="priority" placeholder="Insert value -1023 .. +1023" min="-1023" max="1023" 
				onchange="compose();" onkeypress="compose();"/>
			</div>
		</div>
		<br/>
		
		<!-- Walltime -->
		<div class="input-prepend input-append">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Walltime | -l" data-content="Defines the total amount of time, measured in days, hours, minutes, and seconds (denoted in the form dd+hh:mm:ss) that the computer has to spend on a specific job." 
				data-html="true" data-container="body">Walltime</a>
				<input class="input-block-level input-small" id="appendPrependInput" type="number" 
				name="ResList_HH" placeholder="HH" min="0" max="23" 
				onchange="compose();" onkeypress="compose();"/>
				<span class="add-on">:</span>
				<input class="input-block-level input-small" id="appendPrependInput" type="number" 
				name="ResList_MM" placeholder="MM" min="0" max="59"/>
				<span class="add-on">:</span>
				<input class="input-block-level input-small" id="appendPrependInput" type="number"
				name="ResList_SS" placeholder="SS" min="0" max="59"
				onchange="compose();" onkeypress="compose();"/>
			</div>
		</div>
		<br/>
		
		<!-- Submission Time -->
		<div class="input-prepend input-append">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Submission time | -a" data-content="Declares the time after which the job is  eligible  for  execution." data-html="true" data-container="body">Submission Time</a>
				<input class="input-block-level input-small" id="appendPrependInput" type="number" 
				name="submission_HH" placeholder="HH" min="0" max="23"
				onchange="compose();" onkeypress="compose();"/>
				<span class="add-on">:</span>
				<input class="input-block-level input-small" id="appendPrependInput" type="number" 
				name="submission_MM" placeholder="MM" min="0" max="59"
				onchange="compose();" onkeypress="compose();"/>
			</div>
		</div>
		<br/>
</fieldset>		

<fieldset>
	<legend>Output stream options</legend>
	
	<!-- Defines output path -->
	<div class="input-prepend">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover"
				title="Output Path | -j" data-content="Defines  the  path  to be used for the standard error & error stream of the batch job.  The path argument is of the form:
<pre>[hostname:][path_name]</pre>
where hostname is the name of a host to which the file will  be returned  and  path_name  is  the path name on that host in the syntax recognized by POSIX.  The argument will  be  interpreted as follows:
<ul>
<li><strong>path_name</strong>
Where  path_name  is not an absolute path name, then the qsub command will expand the path name relative  to  the current  working  directory of the command.  The command will supply the name of the host upon which it  is  executing for the hostname component.
</li><li><strong>hostname:path_name</strong>
Where  path_name  is not an absolute path name, then the qsub command will not expand the path name  relative  to the current working directory of the command.  On delivery of  the  standard  error,  the  path  name  will  be expanded  relative  to  the user's home directory on the hostname system.
</li><li><strong>path_name</strong>
Where path_name specifies an absolute  path  name,  then the qsub will supply the name of the host on which it is executing for the hostname
</li><li><strong>hostname:path_name</strong>
Where path_name specifies an  absolute  path  name,  the path will be used as specified.
</li><li><strong>hostname:</strong>
Where  hostname  specifies the name of the host that the file should be returned to. The path will be the default
file name.
</li></ul>
If the -e option is not specified or the path_name is not specified or is specified and is a directory, the default file name for  the  standard error stream will be used.  The default name has the following form:
<pre>job_name.esequence_number</pre>
where job_name is the name of  the  job,  see  -N  option,  and sequence_number is the job number assigned when the job is submitted.
" data-html="true" data-container="body">Output/Error Path</a>
				<input class="input-block-level input-xlarge" id="prependInput" type="text" name="output_path" placeholder="See popup for syntax..." />
			</div>
		</div>
		<br/>
		
		<!-- Stream requested -->
		<div>
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Stream requested | -k" data-content="Defines  which (if either) of standard output or standard error will be retained on the execution host.  If set for  a  stream, this  option  overrides  the path name for that stream.  If not set, neither stream is retained on the execution host." 
				data-html="true" data-container="body">Stream requested</a>
			    <!-- Elemento nascosto -->
			    <input style="display:none;" type="checkbox" name="RequireOutput" 
				id="RequireOutput" checked="true">
			    <input style="display:none;" type="checkbox" name="RequireError" 
				id="RequireError" checked="true">
			    <!-- Elementi visibili -->
			    <div class="btn-group" data-toggle="buttons-checkbox">
					<button type="button" class="btn span2 active" id="RequireOutput-btn" 
						onclick="
							if (document.getElementById('RequireOutput').checked) {
								document.getElementById('RequireOutput').checked = false; }
							else {
								document.getElementById('RequireOutput').checked = true; }
							compose();">Output</button>
					<button type="button" class="btn span2 active" id="RequireError-btn" 
						onclick="
							if (document.getElementById('RequireError').checked) {
								document.getElementById('RequireError').checked = false; }
							else {
								document.getElementById('RequireError').checked = true; }
							compose();">Error</button>
			   </div>
			</div>
		</div>
		<br/>
		
		<!-- Merge stream -->
		<div>
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Merge stream | -j" data-content="Declares if the standard error (output) stream of the job will be merged
               with the standard output (error) stream of the job, or if they will be kept separated." data-html="true" data-container="body">Merge stream</a>
				<!-- Elemento nascosto -->
			   <input style="display:none" type="text" name="MergeOutput" id="MergeOutput" value="">
			   <!-- Elementi visibili -->
				<div class="btn-group" data-toggle="buttons-radio">
					<button type="button" class="btn span2 active" id="MergeOutputSEP" onclick="document.getElementById('MergeOutput').setAttribute('value', '');">Separated</button>
					<button type="button" class="btn span2" id="MergeOutputEO" 
					onclick="
						document.getElementById('MergeOutput').setAttribute('value', '#PBS -j eo');
						compose();">Output in Error</button>
					<button type="button" class="btn span2" id="MergeOutputOE" 
					onclick="
						document.getElementById('MergeOutput').setAttribute('value', '#PBS -j oe');
						compose();">Error in Output</button>
			   </div>
			</div>
		</div>
		<br/>	
</fieldset>

<fieldset>
	<legend>Mail options</legend>
	
	<!-- Mail Events -->
	<div>
		<div class="btn-group">
			<a href="#" class="btn span2 btn-info" data-toggle="popover" 
			title="Mail on event | -m" data-content="Defines the set of conditions under which the execution server will send a mail message about the job." data-html="true" data-container="body">Send mail when job:</a>
		    <!-- Elemento nascosto -->
		    <input style="display:none;" type="checkbox" name="MailBegin" id="MailBegin" checked="true">
		    <input style="display:none;" type="checkbox" name="MailEnd" id="MailEnd" checked="true">
		    <input style="display:none;" type="checkbox" name="MailAbort" id="MailAbort" checked="true">
		    <!-- Elementi visibili -->
		    <div class="btn-group" data-toggle="buttons-checkbox">
				<button type="button" class="btn span2 active" id="Mail-btn" 
					onclick="
						if (document.getElementById('MailBegin').checked) {
							document.getElementById('MailBegin').checked = false; }
						else {
							document.getElementById('MailBegin').checked = true; }
						compose();">Begins</button>
				<button type="button" class="btn span2 active" id="Mail-btn" 
					onclick="
						if (document.getElementById('MailEnd').checked) {
							document.getElementById('MailEnd').checked = false; }
						else {
							document.getElementById('MailEnd').checked = true; }
						compose();">Ends</button>
				<button type="button" class="btn span2 active" id="Mail-btn" 
					onclick="
						if (document.getElementById('MailAbort').checked) {
							document.getElementById('MailAbort').checked = false; }
						else {
							document.getElementById('MailAbort').checked = true; }
						compose();">Aborts</button>
			   </div>
			</div>
		</div>
		<br/>	
		
		<!-- Mail Adresses -->
		<div class="input-prepend">
			<div class="btn-group">
				<a href="#" class="btn span2 btn-info" data-toggle="popover" 
				title="Mail address | -M" data-content="<p>Declares the list of users to whom mail is sent by  the  execu-
               tion server when it sends mail about the job.</p><p>The user_list argument is of the form:<pre>user[@host][,user[@host],...]</pre>If  unset, the list defaults to the submitting user at the qsub host, i.e. the job owner.</p>" 
			   data-html="true" data-container="body">Mail Address</a>
				<input class="input-block-level input-xlarge" id="prependInput" type="text" 
				name="mail_addr" placeholder="Insert mail addresses, comma separated" 
				onchange="compose();" onkeypress="compose();" />
			</div>
		</div>
		<br/>
		
		
</fieldset>

<fieldset>
	<legend>Script body<legend>
		<div>
		<textarea id="bash_body" class="input-block-level" rows="5" 
		placeholder="Insert bash script body here..." name="bash_body"
		onkeypress="compose();" onchange="compose();"></textarea>
		<p>
		<button class="btn btn-small" data-toggle="modal" data-target="#envvars-modal">Show envvars</button>
		</p>
		</div>
</fieldset>		

<fieldset>
	<legend>Result<legend>
	<pre id="results_code" class="input-bloc-level"></pre>
	<a style="display:none;" id="results-btn" class="btn btn-primary" onclick="compose()">Compose</a><br/>
</fieldset>
</form>

<form class="form-actions" method="POST" action="submit_run.php"> 
	<input id="result_script" type="text" name="result_script" style="display:none;">
	<p align="right">
	<button type="submit" class="btn btn-primary btn-large"><i class="icon-download-alt icon-white" ></i> Save and submit PBS script</button>
	</p>
</form>

<!-- Modal delle envvars -->
<div id="envvars-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">EnvVariables</h3>
  </div>
  <div class="modal-body">
    <p>
		<h4>Enviromental variables</h4>
		<table class="table">
			<thead><tr>
				<th>EnvVar Name</th>
				<th>... becomes</th>
				<th>Value</th>
			</tr></thead>
			<tbody>
				<tr>
				<td class="tdelem">$USER</td>
				<td class="tdelem"></td>
				<td><pre><code><? echo ssh2_exec2($_SESSION['username'],$_SESSION['password'],'echo $USER'); ?></code></pre></td>
				</tr>
				<tr>
				<td class="tdelem">$UID</td>
				<td class="tdelem"></td>
				<td><pre><code><? echo ssh2_exec2($_SESSION['username'],$_SESSION['password'],'echo $UID'); ?></code></pre></td>
				</tr>
				<tr>
				<td class="tdelem">$HOME</td>
				<td class="tdelem">$PBS_O_HOME</td>
				<td><pre><code><? echo ssh2_exec2($_SESSION['username'],$_SESSION['password'],'echo $HOME'); ?></code></pre></td>
				</tr>
				<tr>
				<td class="tdelem">$PATH</td>
				<td class="tdelem">$PBS_O_PATH</td>
				<td><pre><code><? echo ssh2_exec2($_SESSION['username'],$_SESSION['password'],'echo $PATH'); ?></code></pre></td>
				</tr>
				<tr>
				<td class="tdelem">$SHELL</td>
				<td class="tdelem">$PBS_O_SHELL</td>
				<td><pre><code><? echo ssh2_exec2($_SESSION['username'],$_SESSION['password'],'echo $SHELL'); ?></code></pre></td>
				</tr>
				<tr>
				<td class="tdelem">$LD_LIBRARY_PATH</td>
				<td class="tdelem"></td>
				<td><pre><code><? echo ssh2_exec2($_SESSION['username'],$_SESSION['password'],'echo $LD_LIBRARY_PATH'); ?></code></pre></td>
				</tr>
				<tr>
				<td class="tdelem">$HOSTNAME</td>
				<td class="tdelem"></td>
				<td><pre><code><? echo ssh2_exec2($_SESSION['username'],$_SESSION['password'],'echo $HOSTNAME'); ?></code></pre></td>
				</tr>
			</tbody>
		</table>
	</p>
	<p>
		<h4>PBS Variables</h4>
		<table class="table">
			<thead><tr>
				<th>Variable name</th>
				<th>Description</th>
			</tr></thead>
			<tbody>
				<tr>
				<td class="tdelem">$PBS_O_HOST</td>
				<td>the name of the host upon which the qsub command is running.</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_SERVER</td>
				<td>the hostname of the pbs_server which qsub submits the job to.</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_O_QUEUE</td>
				<td>the name of the original queue to which the job was submitted.</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_O_WORKDIR</td>
				<td>the  absolute  path of the current working directory of the qsub command.</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_ARRAYID</td>
				<td>each member of a job array is assigned a unique identifier (see -t)</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_ENVIROMENT</td>
				<td>set  to  PBS_BATCH  to  indicate  the  job is a batch job, or to PBS_INTERACTIVE to indicate the job is a  PBS  interactive  job, see -I option.</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_JOBID</td>
				<td>the job identifier assigned to the job by the batch system.</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_JOBNAME</td>
				<td>the job name supplied by the user.</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_NODEFILE</td>
				<td>the  name  of the file contain the list of nodes assigned to the job (for parallel and cluster systems).</td>
				</tr>
				<tr>
				<td class="tdelem">$PBS_QUEUE</td>
				<td>the name of the queue from which the job is executed.</td>
				</tr>
			</tbody>
		</table>
	</p>
   </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<!--
	TODO
	* Require GPUS sostituire la textbox con una checkbox!
-->
<script> compose(); </script>