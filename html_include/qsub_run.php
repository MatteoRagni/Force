<form class="form-actions">
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
 
function Popup(apri) {
  window.open(encodeURI(apri), "", "top=10, left=10, width=800, height=600, status=no, menubar=no, toolbar=no scrollbars=no");
}
</script>

<? include('html_include/tree_explorer.php'); ?>

