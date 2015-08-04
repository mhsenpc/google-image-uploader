<?php
// load classes
set_include_path(implode(PATH_SEPARATOR, array(
    realpath('ZendGdata-1.12.3/library'),
    get_include_path(),
    )));
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_Query');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Photos');
Zend_Loader::loadClass('Zend_Gdata_Photos_UserQuery');
Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumQuery');
Zend_Loader::loadClass('Zend_Gdata_Photos_PhotoQuery');

function UploadInPicasa($filename)
{

    $serviceName = Zend_Gdata_Photos::AUTH_SERVICE_NAME;
    $user = "";
    $pass = "";

    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $serviceName);

    // update the second argument to be CompanyName-ProductName-Version
    $gp = new Zend_Gdata_Photos($client, "mohsen-soft");
    $username = "default";
    $photoName = "fbtoblog.com_".strtotime('+1 minutes') ;
    $photoCaption = "The first photo I uploaded to Picasa Web Albums via PHP.";
    $photoTags = "beach, sunshine";

    // We use the albumId of 'default' to indicate that we'd like to upload
    // this photo into the 'drop box'.  This drop box album is automatically
    // created if it does not already exist.
    $albumId = "default";

    $fd = $gp->newMediaFileSource($filename);
    $fd->setContentType("image/jpeg");

    // Create a PhotoEntry
    $photoEntry = $gp->newPhotoEntry();

    $photoEntry->setMediaSource($fd);
    $photoEntry->setTitle($gp->newTitle($photoName));
    $photoEntry->setSummary($gp->newSummary($photoCaption));

    // add some tags
    $keywords = new Zend_Gdata_Media_Extension_MediaKeywords();
    $keywords->setText($photoTags);
    $photoEntry->mediaGroup = new Zend_Gdata_Media_Extension_MediaGroup();
    $photoEntry->mediaGroup->keywords = $keywords;

    // We use the AlbumQuery class to generate the URL for the album
    $albumQuery = $gp->newAlbumQuery();

    $albumQuery->setUser($username);
    $albumQuery->setAlbumId($albumId);

    // We insert the photo, and the server returns the entry representing
    // that photo after it is uploaded
    $insertedEntry = $gp->insertPhotoEntry($photoEntry, $albumQuery->getQueryUrl());
    $mediaContentArray = $insertedEntry->getMediaGroup()->getContent();
    $mediaThumbnailArray = $insertedEntry->getMediaGroup()->getThumbnail();

    $fullsize = $mediaContentArray[0]->getUrl();
    $thumb72 = $mediaThumbnailArray[0]->getUrl();
      $arr = array(
        'fullsize' => $fullsize,
        'thumb72' => $thumb72,

        );
    return $arr;
}
