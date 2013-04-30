<?php
  /* File upload progress in PHP 5.4 */
  
  /* needs a 5.4+ version */
  $version = explode( '.', phpversion() );											//
  if ( ($version[0] * 10000 + $version[1] * 100 + $version[2]) < 50400 )			//
    die( 'PHP 5.4.0 or higher is required' );										// -> questa parte fa il check della versione

  if ( !intval(ini_get('session.upload_progress.enabled')) )						//
    die( 'session.upload_progress.enabled is not enabled' );						// -> questa parte controlla che sia abilitato il progress on upload

  session_start();																	// sessione registrata - ce l'ho già

  if ( isset( $_GET['progress'] ) ) {												// -> se c'è la variabile progress settata nel get

    $progress_key = strtolower(ini_get("session.upload_progress.prefix").'demo');	
  
    if ( !isset( $_SESSION[$progress_key] ) ) exit( "uploading..." );

    $upload_progress = $_SESSION[$progress_key];
    /* get percentage */
    $progress = round( ($upload_progress['bytes_processed'] / $upload_progress['content_length']) * 100, 2 );

    exit( "Upload progress: $progress%" );
  }  
?>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>

<?php if ( isset($_GET['iframe']) ): /* thank you Webkit... */ ?>
<form action="" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="demo">
  <input type="file" name="uploaded_file">
  <input type="submit" value="Upload">
</form>

<script type="text/javascript">
window.location.hash = ""; /* reset */
jQuery("form").bind("submit", function() { window.location.hash = "uploading"; });
</script>

<?php else: ?>

<iframe src="?iframe" id="upload_form"></iframe>
<script type="text/javascript">
  jQuery(document).ready(init);

  function init() {
    /* start listening on submit */
    update_file_upload_progress();
  }

  function update_file_upload_progress() {
    if ( window.frames.upload_form.location.hash != "#uploading" ) {
      setTimeout( update_file_upload_progress, 100 ); /* has upload started yet? */
      return;
    }
    $.get( /* lather */
      "?progress",
      function(data) {
        /* rinse */
        jQuery("#file_upload_progress").html(data);
        /* repeat */
        setTimeout( update_file_upload_progress, 500 );
      }
    ).error(function(jqXHR, error) { alert(error); });
  }
</script>

<div id="file_upload_progress"></div>
<?php endif; ?>