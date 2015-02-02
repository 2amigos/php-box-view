<?php
/**
 * Bootstrap
 */
error_reporting(E_ALL);
$exampleApiKey = 'YOUR_API_KEY';

// set the content type to plaintext if we're running this from a web browser
if (php_sapi_name() != 'cli') {
    header('Content-Type: text/plain');
}

require_once __DIR__ . '/../vendor/autoload.php';
Box\View\Client::setApiKey($exampleApiKey);

// when did this script start?
date_default_timezone_set('America/Los_Angeles');
$start = date('c');

// set a couple document variables we'll use later
$document = null;
$document2 = null;

/*
 * Example #1
 * 
 * Upload a file. We're uploading a sample file by URL.
 */
echo 'Example #1 - Upload sample file by URL.' . "\n";
$sampleUrl = 'http://crocodoc.github.io/php-box-view/examples/files/sample.doc';
echo '  Uploading... ';

try {
    $document = Box\View\Document::uploadUrl($sampleUrl, [
        'name' => 'Sample File',
    ]);
    echo 'success :)' . "\n";
    echo '  ID is ' . $document['id'] . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #2
 * 
 * Check the metadata of the file from Example #1.
 */
echo "\n";
echo 'Example #2 - Check the metadata of the file we just uploaded.' . "\n";
echo '  Checking metadata... ';

try {
    $metadata = Box\View\Document::metadata($document['id'], [
        'id',
        'type',
        'status',
        'name',
        'created_at',
    ]);

    echo 'success :)' . "\n";
    echo '  File id is ' . $metadata['id'] . '.' . "\n";
    echo '  File type is ' . $metadata['type'] . '.' . "\n";
    echo '  File status is ' . $metadata['status'] . '.' . "\n";
    echo '  File name is ' . $metadata['name'] . '.' . "\n";
    echo '  File was created on ' . $metadata['created_at'] . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #3
 * 
 * Upload another file. We're uploading a sample .doc file from the local
 * filesystem using all options.
 */
echo "\n";
echo 'Example #3 - Upload a sample .doc as a file using all options.' . "\n";
$filePath = __DIR__ . '/files/sample.doc';

if (is_file($filePath)) {    
    $handle = fopen($filePath, 'r');
    echo '  Uploading... ';
    
    try {
        $document2 = Box\View\Document::uploadFile($handle, [
            'name' => 'Sample File #2',
            'thumbnails' => ['100x100', '200x200'],
            'nonSvg' => true,
        ]);
        echo 'success :)' . "\n";
        echo '  ID is ' . $document2['id'] . "\n";
    } catch (Box\View\Exception $e) {
        echo 'failed :(' . "\n";
        echo '  Error Code: ' . $e->errorCode . "\n";
        echo '  Error Message: ' . $e->getMessage() . "\n";
    }
} else {
    echo '  Skipping because the sample .doc file can\'t be found.' . "\n";
}

/*
 * Example #4
 * 
 * Check the metadata of the file from Example #3.
 */
echo "\n";
echo 'Example #4 - Check the metadata of the file we just uploaded.' . "\n";
echo '  Checking metadata... ';

try {
    $metadata = Box\View\Document::metadata($document2['id'], [
        'id',
        'type',
        'status',
        'name',
        'created_at',
    ]);

    echo 'success :)' . "\n";
    echo '  File id is ' . $metadata['id'] . '.' . "\n";
    echo '  File type is ' . $metadata['type'] . '.' . "\n";
    echo '  File status is ' . $metadata['status'] . '.' . "\n";
    echo '  File name is ' . $metadata['name'] . '.' . "\n";
    echo '  File was created on ' . $metadata['created_at'] . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #5
 * 
 * List the documents we've uploaded since starting these examples.
 */
echo "\n";
echo 'Example #5 - List the documents we uploaded so far.' . "\n";
echo '  Listing documents... ';

try {
    $documents = Box\View\Document::listDocuments([
        'createdAfter' => $start,
    ]);
    $doc1 = $documents['document_collection']['entries'][1];
    $doc2 = $documents['document_collection']['entries'][0];
    echo 'success :)' . "\n";
    echo '  File #1 id is ' . $doc1['id'] . '.' . "\n";
    echo '  File #1 type is ' . $doc1['type'] . '.' . "\n";
    echo '  File #1 status is ' . $doc1['status'] . '.' . "\n";
    echo '  File #1 name is ' . $doc1['name'] . '.' . "\n";
    echo '  File #1 was created on ' . $doc1['created_at'] . '.' . "\n";
    echo '  File #2 id is ' . $doc2['id'] . '.' . "\n";
    echo '  File #2 type is ' . $doc2['type'] . '.' . "\n";
    echo '  File #2 status is ' . $doc2['status'] . '.' . "\n";
    echo '  File #2 name is ' . $doc2['name'] . '.' . "\n";
    echo '  File #2 was created on ' . $doc2['created_at'] . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #6
 * 
 * Wait ten seconds and check the status of both files.
 */
echo "\n";
echo 'Example #6 - Wait ten seconds and check the status of both files.' . "\n";
echo '  Waiting... ';
sleep(10);
echo 'done.' . "\n";
echo '  Checking statuses... ';

try {
    $documents = Box\View\Document::listDocuments([
        'createdAfter' => $start,
    ]);
    $doc1 = $documents['document_collection']['entries'][1];
    $doc2 = $documents['document_collection']['entries'][0];
    echo 'success :)' . "\n";
    echo '  Status for file #1 (id=' . $doc1['id'] . ') is ' . $doc1['status']
         . '.' . "\n";
    echo '  Status for file #2 (id=' . $doc2['id'] . ') is ' . $doc2['status']
         . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #7
 * 
 * Delete the file we uploaded from Example #1.
 */
echo "\n";
echo 'Example #7 - Delete the second file we uploaded.' . "\n";
echo '  Deleting... ';

try {
    $deleted = Box\View\Document::delete($document2['id']);

    if ($deleted) {
        echo 'success :)' . "\n";
        echo '  File was deleted.' . "\n";
    } else {
        echo 'failed :(' . "\n";
    }
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #8
 * 
 * Update the name of the file from Example #1.
 */
echo "\n";
echo 'Example #8 - Update the name of a file.' . "\n";
echo '  Updating... ';

try {
    $metadata = Box\View\Document::update($document['id'], [
        'name' => 'Updated Name',
    ]);
    echo 'success :)' . "\n";
    echo '  File id is ' . $metadata['id'] . '.' . "\n";
    echo '  File type is ' . $metadata['type'] . '.' . "\n";
    echo '  File status is ' . $metadata['status'] . '.' . "\n";
    echo '  File name is ' . $metadata['name'] . '.' . "\n";
    echo '  File was created on ' . $metadata['created_at'] . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #9
 * 
 * Download the file we uploaded from Example #1 in its original file format.
 */
echo "\n";
echo 'Example #9 - Download a file in its original file format.' . "\n";
echo '  Downloading... ';

try {
    $contents = Box\View\Document::download($document['id']);
    $filename = __DIR__ . '/files/test-original.doc';
    $handle = fopen($filename, 'w');
    fwrite($handle, $contents);
    fclose($handle);
    echo 'success :)' . "\n";
    echo '  File was downloaded to ' . $filename . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #10
 * 
 * Download the file we uploaded from Example #1 as a PDF.
 */
echo "\n";
echo 'Example #10 - Download a file as a PDF.' . "\n";
echo '  Downloading... ';

try {
    $contents = Box\View\Document::download($document['id'], 'pdf');
    $filename = __DIR__ . '/files/test.pdf';
    $handle = fopen($filename, 'w');
    fwrite($handle, $contents);
    fclose($handle);
    echo 'success :)' . "\n";
    echo '  File was downloaded to ' . $filename . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #11
 * 
 * Download the file we uploaded from Example #1 as a zip file.
 */
echo "\n";
echo 'Example #11 - Download a file as a zip.' . "\n";
echo '  Downloading... ';

try {
    $contents = Box\View\Document::download($document['id'], 'zip');
    $filename = __DIR__ . '/files/test.zip';
    $handle = fopen($filename, 'w');
    fwrite($handle, $contents);
    fclose($handle);
    echo 'success :)' . "\n";
    echo '  File was downloaded to ' . $filename . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #12
 * 
 * Download the file we uploaded from Example #1 as a small thumbnail.
 */
echo "\n";
echo 'Example #12 - Download a small thumbnail from a file.' . "\n";
echo '  Downloading... ';

try {
    $thumbnailContents = Box\View\Document::thumbnail($document['id'], 16, 16);
    $filename = __DIR__ . '/files/test-thumbnail.png';
    $handle = fopen($filename, 'w');
    fwrite($handle, $thumbnailContents);
    fclose($handle);
    echo 'success :)' . "\n";
    echo '  File was downloaded to ' . $filename . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #13
 * 
 * Download the file we uploaded from Example #1 as a large thumbnail.
 */
echo "\n";
echo 'Example #13 - Download a large thumbnail from a file.' . "\n";
echo '  Downloading... ';

try {
    $thumbnailContents = Box\View\Document::thumbnail($document['id'], 250, 250);
    $filename = __DIR__ . '/files/test-thumbnail-large.png';
    $handle = fopen($filename, 'w');
    fwrite($handle, $thumbnailContents);
    fclose($handle);
    echo 'success :)' . "\n";
    echo '  File was downloaded to ' . $filename . '.' . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #14
 * 
 * Create a session for the file we uploaded from Example #1 with default
 * options.
 */
echo "\n";
echo 'Example #14 - Create a session for a file with default options.' . "\n";
echo '  Creating... ';
$session = null;

try {
    $session = Box\View\Session::create($document['id']);
    echo 'success :)' . "\n";
    echo '  Session id is ' . $session['id'] . '.' . "\n";
    echo '  Session expires on ' . $session['expires_at'] . '.' . "\n";
    echo '  Session view URL is ' . $session['urls']['view'] . '.' . "\n";
    echo '  Session assets URL is ' . $session['urls']['assets'] . '.' . "\n";
    echo '  Session realtime URL is ' . $session['urls']['realtime'] . '.'
         . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #15
 * 
 * Create a session for the file we uploaded from Example #1 all of the options.
 */
echo "\n";
echo 'Example #15 - Create a session for a file with more of the options.'
     . "\n";
echo '  Creating... ';
$session2 = null;

try {
    $session2 = Box\View\Session::create($document['id'], [
        'expiresAt' => date('c', strtotime('+10 min')),
        'isDownloadable' => true,
        'isTextSelectable' => false,
    ]);
    echo 'success :)' . "\n";
    echo '  Session id is ' . $session2['id'] . '.' . "\n";
    echo '  Session expires on ' . $session2['expires_at'] . '.' . "\n";
    echo '  Session view URL is ' . $session2['urls']['view'] . '.' . "\n";
    echo '  Session assets URL is ' . $session2['urls']['assets'] . '.' . "\n";
    echo '  Session realtime URL is ' . $session2['urls']['realtime'] . '.'
         . "\n";
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #16
 * 
 * Delete the two sessions.
 */
echo "\n";
echo 'Example #16 - Delete the two sessions.' . "\n";
echo '  Deleting... ';

try {
    $deleted = Box\View\Session::delete($session['id']);

    if ($deleted) {
        echo 'success :)' . "\n";
        echo '  Session #1 was deleted.' . "\n";
    } else {
        echo 'failed :(' . "\n";
    }
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

try {
    $deleted = Box\View\Session::delete($session2['id']);

    if ($deleted) {
        echo 'success :)' . "\n";
        echo '  Session #2 was deleted.' . "\n";
    } else {
        echo 'failed :(' . "\n";
    }
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}

/*
 * Example #17
 * 
 * Delete the file we uploaded from Example #1.
 */
echo "\n";
echo 'Example #17 - Delete the first file we uploaded.' . "\n";
echo '  Deleting... ';

try {
    $deleted = Box\View\Document::delete($document['id']);

    if ($deleted) {
        echo 'success :)' . "\n";
        echo '  File was deleted.' . "\n";
    } else {
        echo 'failed :(' . "\n";
    }
} catch (Box\View\Exception $e) {
    echo 'failed :(' . "\n";
    echo '  Error Code: ' . $e->errorCode . "\n";
    echo '  Error Message: ' . $e->getMessage() . "\n";
}
