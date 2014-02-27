<?php
require 'vendor/autoload.php';
require 'enkey.php';

function get_param($name, $default) {
	$field = $_GET;
	if (isset($field[$name])) {
		if (!empty($field[$name])){
			return $field[$name];
		}
	}
	return $default;
}

function init_evernote($token) {
	global $ENCLIENT, $NS;
	$ENCLIENT = new Evernote\Client(array('token' => $token));
	$NS = $ENCLIENT->getNoteStore();
}

function get_notes($filter) {
	global $ENCLIENT, $NS;
	$spec = new EDAM\NoteStore\NotesMetadataResultSpec();
	$spec->includeTitle = True;
	 
	$notelist = $NS->findNotesMetadata($filter, 0, 1, $spec);
	 
	$c = '';
	$notes = array();
	foreach($notelist->notes as $n) {
		$notes[] = $NS->getNote($n->guid, true,false,false,false);
	}
	return $notes;
}

function get_template() {
	$template = file_get_contents(__DIR__.'/page.tpl');
	return $template;
}

function get_page($body,$header='') {
	$template = get_template();
	$page = str_replace("{%%HEADCONTENT%%}", $header, $template);	//replace head maerk
	$page = str_replace("{%%BODYCONTENT%%}", $body, $page);
	return $page;
}

function format_note($note) {
	$out = '';
	
	$out .= "<h1>{$note->title}</h1>";	
	$out .= "{$note->content}";

	return $out;
}
