<?php
require 'vendor/autoload.php';
require 'devkey.php';
$noteStoreUrl = 'https://sandbox.evernote.com/shard/s1/notestore';
 
$client = new Evernote\Client(array('token' => $devToken));
$noteStore = $client->getNoteStore();
//$notebooks = $noteStore->listNotebooks();
//var_dump($notebooks);
$shoppinglistguid = '39c5c5d0-6ee5-457f-ba79-9b4481b59faf';
$filter = new EDAM\NoteStore\NoteFilter();
$filter->tagGuids = [$shoppinglistguid];
$filter->ascending = false;
//var_dump($filter);
 
$spec = new EDAM\NoteStore\NotesMetadataResultSpec();
$spec->includeTitle = True;
 
$notelist = $noteStore->findNotesMetadata($filter, 0, 1, $spec);
 
$c = '';
foreach($notelist->notes as $n) {
//var_dump($n);
$fullnote = $noteStore->getNote($n->guid, true,false,false,false);
//var_dump($fullnote);
$title = $fullnote->title;
$content = $fullnote->content;
//var_dump($content);
$c .= "<h1>{$fullnote->title}</h1>";
$c .= $content.'<hr/>';
}
send_to_lp($c, "body{ font-size:12pt}\nen-todo { height:10px; width:10px;border: 1px solid black; display:inline-block; margin-right:3px}\n", true);
 
function send_to_lp($html, $style = '', $dump = false) {
$ohtml = "<html><head><meta charset=\"utf-8\"><style>{$style}</style></head><body>";
$ohtml .=$html;
$ohtml .= '</body></html>';
if ($dump) {
echo $ohtml;
return;
}
 
$key = 'JXSKYUWFV98M';
$ch = curl_init('http://remote.bergcloud.com/playground/direct_print/'.$key);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'html='. urlencode($html));
curl_exec($ch);
 
}
/*
$client = new Evernote\Client(array(
'consumerKey' => 'mhughes2k-4552',
'consumerSecret' => '554c0f13af5176ad'
));
 
$requestToken = $client->getRequestToken('');
$authorizeUrl = $client->getAuthorizeUrl($requestToken['oauth_token']);
 
$accessToken = $client->getAccessToken(
$requestToken['oauth_token'],
$requestToken['oauth_token_secret'],
$_GET['oauth_verifier']
);
 
$token = $accessToken['oauth_token'];
$client = new Evernote\Client(array('token' => $token));
 
$notestore = $client->getNoteStore();
$notebooks = $notestore->listNotebooks();
*/

