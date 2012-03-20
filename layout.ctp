<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo !empty($table) ? $table.' * ' : '' ?>DB Viewer</title>
    <link href="/favicon.ico" type="image/x-icon" rel="icon" />
    <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="js/jquery.mCustomScrollbar.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</head>
<body>
<div class="main">
    <div class="container tables">
        <p><strong>DB tables:</strong></p>
        <?php if (!empty($tables)): ?>
            <ul>
                <?php foreach ($tables as $item): ?>
                    <li>
                        <?php if (!empty($table) && $table == $item[0]): ?>
                            <span><?php echo $item[0]; ?></span>
                        <?php else: ?>
                            <a href="?table=<?php echo $item[0]; ?>"><?php echo $item[0]; ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php else: ?>
            <p>Empty tables list.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($debug_output)): ?>
        <div class="container debug"><?php echo $debug_output; ?></div>
    <?php endif ?>

    <div class="container controls">
        <div class="column left">
            <p><strong>Some controls</strong></p>
            <?php if (!empty($table)): ?>
                <form method="GET">
                    <div class="limit">
                        <label>Limit:</label>
                        <input type="hidden" name="table" value="<?php echo $table; ?>">
                        <input type="text" name="per_page" value="<?php echo $pager['per_page']; ?>">
                        <em>(* for all)</em>
                    </div>

                    <div class="pages">
                        <label>Page: </label>

                        <?php if ($pager['page'] != 1): ?>

                            <a class="prev" href="?table=<?php echo $table; ?>&per_page=<?php echo $pager['per_page']; ?>&page=<?php echo $pager['page']-1; ?>&sort=<?php echo $pager['sort']; ?>&dir=<?php echo $pager['dir']; ?>"></a>
                        <?php else: ?>
                            <span class="prev"></span>
                        <?php endif; ?>

                        <input type="text" name="page" value="<?php echo $pager['page']; ?>">
                        <input type="hidden" name="sort" value="<?php echo $pager['sort']; ?>">
                        <input type="hidden" name="dir" value="<?php echo $pager['dir']; ?>">

                        <?php if ($pager['page'] != $pager['last_page'] && $pager['last_page'] != 0): ?>
                            <a class="next" href="?table=<?php echo $table; ?>&per_page=<?php echo $pager['per_page']; ?>&page=<?php echo $pager['page']+1; ?>&sort=<?php echo $pager['sort']; ?>&dir=<?php echo $pager['dir']; ?>"></a>
                        <?php else: ?>
                            <span class="next"></span>
                        <?php endif; ?>

                        <strong>Total: <?php echo $pager['last_page'] ?></strong>
                    </div>
                </form>
            <?php endif ?>
        </div>
        <div class="column right">
            <p><strong>Hints:</strong></p>
            <p>- use Alt key to change scroll direction</p>
            <p>- use * to switch off limit (slow on over 1k entries)</p>
            <p>- use IE only for you own risk and harm of heals</p>
        </div>
    </div>

    <div class="container results">
        <div class="data">
            <?php if (!empty($data)): ?>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                    <?php foreach ($data[0] as $key => $value): ?>
                        <th style="min-width:<?php echo strlen($key) * 9 + 12; ?>px;">
                            <a href="?table=<?php echo $table; ?>&per_page=<?php echo $pager['per_page']; ?>&page=<?php echo $pager['page']; ?>&sort=<?php echo $key; ?>&dir=<?php echo ($key == $pager['sort'] ? $pager['dir_r'] : 'asc'); ?>" class="<?php echo ($key == $pager['sort'] ? $pager['dir'] : ''); ?>"><?php echo $key; ?><span></span></a>
                        </th>
                    <?php endforeach ?>
                    </tr>
                    <?php foreach ($data as $key => $item): ?>
                        <tr class="<?php echo ($key%2 == 0) ? 'diff' : ''; ?>">
                            <?php foreach ($item as $value): ?>
                                <?php $short_str = strlen($value) < 30 ? $value : substr($value, 0, 28).'..' ?>
                                <td style="min-width:<?php echo strlen($short_str) * 9; ?>px;" title="<?php echo htmlspecialchars($value); ?>">
                                    <?php echo $short_str !== null ? htmlspecialchars($short_str) : 'null'; ?>
                                </td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                    <?php foreach ($data[0] as $key => $value): ?>
                        <th style="min-width:<?php echo strlen($key) * 9 + 12; ?>px;">
                            <a href="?table=<?php echo $table; ?>&per_page=<?php echo $pager['per_page']; ?>&page=<?php echo $pager['page']; ?>&sort=<?php echo $key; ?>&dir=<?php echo ($key == $pager['sort'] ? $pager['dir_r'] : 'asc'); ?>" class="<?php echo ($key == $pager['sort'] ? $pager['dir'] : ''); ?>"><?php echo $key; ?><span></span></a>
                        </th>
                    <?php endforeach ?>
                    </tr>
                </table>
            <?php else: ?>
                <p>Table is empty or aliens stolen server...</p>
            <?php endif ?>
        </div>
    </div>
    <div class="container footer">
        <p>&copy;<?php echo date('Y') ?> made by darkside with help of cookies..</p>
    </div>
</div>
</body>
</html>
