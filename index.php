<?php
require_once('config.php');

$debug_output = '';
$pager = array();

try {
    // connect to database
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // get tables list from cache or database
    if (file_exists(CACHE_FILE) && (time() - filemtime(CACHE_FILE)) < 86400) {
        $tables = unserialize(file_get_contents(CACHE_FILE));
    } else {
        $stm = $db->query("SHOW TABLES");
        $tables = $stm->fetchAll(PDO::FETCH_NUM);
        file_put_contents(CACHE_FILE, serialize($tables));
    }

    // get table entries
    if (!empty($_GET['table']) && in_array(array($_GET['table']), $tables)) {
        $table = $_GET['table'];
        // total entries in table
        $stm = $db->query("SELECT COUNT(*) FROM {$table}");
        $count = $stm->fetch(PDO::FETCH_NUM);
        // page params
        $pager['total'] = $count[0];
        $pager['page'] = (empty($_GET['page']) || intval($_GET['page']) == 0)  ? 1 : $_GET['page'];
        $pager['per_page'] = (empty($_GET['per_page']) || intval($_GET['per_page']) == 0)  ? 20 : $_GET['per_page'];
        $pager['last_page'] = ceil($pager['total']/$pager['per_page']);
        // with no limit
        if (!empty($_GET['per_page']) && $_GET['per_page'] === '*') {
            $pager['per_page'] = $pager['total'];
            $pager['page'] = 1;
            $pager['last_page'] = 1;
        }
        // sort params
        $pager['sort'] = (empty($_GET['sort'])) ? 'id' : substr($db->quote($_GET['sort']), 1, -1);
        $pager['dir'] = (empty($_GET['dir']) || !in_array(strtolower($_GET['dir']), array('asc', 'desc'))) ? 'desc' : strtolower($_GET['dir']);
        $pager['dir_r'] = ($pager['dir'] == 'desc') ? 'asc' : 'desc';

        $start_limit = $pager['per_page']*($pager['page']-1);
        $end_limit = $pager['per_page'];
        // get needed entries
        $stm = $db->prepare("SELECT * FROM {$table} ORDER BY {$pager['sort']} {$pager['dir']} LIMIT {$start_limit}, {$end_limit}");
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
    }

} catch(PDOException $e) {
    $echo = "Sorry, error has occured while processing your query. Forget about this and close window, dance \"Macarena\" or ask Oleg about problem.";
    $msg = date('Y-m-d H:i').' '.$e->getMessage()."\r\n";
    file_put_contents('tmp/error.log', $msg, FILE_APPEND);
}

// attach view
require_once('layout.ctp');


function debug($v) {
    global $debug_output;
    $trace = debug_backtrace();
    $debug_output .= "<p>Debug output from <b>".$trace[0]['file']."</b> on line <b>".$trace[0]['line']."</b></p>";
    $debug_output .= "<pre>".print_r($v, true)."</pre><br/>";
}
