<form class="form-actions"  method="POST" action="job_rerun.php" onSubmit="return popWindow(this.target)" target="Details">
	<div class="input-prepend input-append">
		<span class="add-on"><? echo '<strong>' . $_SESSION['username'] . ':~$ qsub</strong> ' . $_SESSION['home'] . '/ '; ?></span>  
		<input align="center"	class="input-block-level input-xxlarge" id="job_script_name" type="text" name="relative_path" 
				placeholder="Insert script relative to your home path" >
		<button type="submit" class="btn btn-primary input-append"><i class="icon-circle-arrow-right icon-white"></i> Launch!</button>
	</div>
</form>

<script type="text/javascript"> 
function click_fun(obj) {
	var path = obj.innerHTML;
	$('#job_script_name').val(path);
} 
function click_fun2(path) {
	$('#job_script_name').val(path);
}
 
function click_delete(path) {
	if(confirm("Are you sure? Delete: "+path)){
		location.href = "delete.php?file="+path;
	}
}
 
function Popup(apri) {
  window.open(encodeURI(apri), "", "top=10, left=10, width=800, height=600, status=no, menubar=no, toolbar=no scrollbars=no");
}

function popWindow(wName){
	features = 'width=400,height=400,toolbar=no,location=no,directories=no,menubar=no,scrollbars=no,copyhistory=no';
	pop = window.open('',wName,features);
	if(pop.focus){ pop.focus(); }
	return true;
}
</script>

<? include('html_include/tree_explorer.php'); ?>

