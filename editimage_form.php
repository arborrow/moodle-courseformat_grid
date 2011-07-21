<?php $upload_max_filesize = get_max_upload_file_size($CFG->maxbytes); ?>

<script type="text/javascript">
function checkvalue(elm,formname) {
    var el = document.getElementById(elm);
    if(!el.value) {
        alert("Nothing to do!");
        el.focus();
        return false;
    }
}
</script>
<form id="theform" method="post" enctype="multipart/form-data" action="editimage.php">
<table summary="Summary of image" cellpadding="5" class="boxaligncenter">
<tr valign="top">
    <td align="right">
      <p><b><?php print_string("image","format_grid") ?></b></p>
    </td>
    <td>
          <input type="file" name="userfile" id="userfile" size="35" />
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php print($upload_max_filesize);?>" />
          <input type="hidden" name="id" VALUE="<?php print($id);?>" />
          <input type="hidden" name="wdir" value="" />
          <input type="hidden" name="action" value="upload" />
          <input type="hidden" name="sesskey" value="<?php p($USER->sesskey) ?>" />
          <input name="save" type="submit" id="save" onclick="return checkvalue('userfile','uploader');" value="Save" /> 
          
    </td>
</tr>
</table>
</form>
