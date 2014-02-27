<?php
require '../lib.php';
require '../enkey.php';

ini_set('display_errors','1');
$deliverycount = get_param('delivery_count', false);

$tagguid = get_param('listtagguid', false);

$etagval = md5($deliverycount);

//fetch shopping list notes:
init_evernote($devToken);

$filter = new EDAM\NoteStore\NoteFilter();
$filter->tagGuids = [$tagguid];
$filter->ascending = false;

$notes = get_notes($filter);

$body ='';
foreach($notes as $note) {
	$body.=format_note($note);
}
$etagval = md5($devToken.$body);
//set ETag
header('ETag:"' .$etagval.'"');



echo get_page($body);
	 


