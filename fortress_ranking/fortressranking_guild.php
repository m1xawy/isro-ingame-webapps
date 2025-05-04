<?php
require_once 'config.php';

$file = $_SERVER['DOCUMENT_ROOT'] . '/cache/fortressranking_guild.json';
$time = 15;

if (!file_exists($file) || filemtime($file) < time() - $time) {
$query = "SELECT TOP(10) 
            _Guild.ID, 
            _Guild.Name, 
    
            (SELECT SUM(GuildWarKill) from _GuildMember WHERE GuildID = _Guild.ID) AS TotalKills, 
            (SELECT SUM(GuildWarKilled) from _GuildMember WHERE GuildID = _Guild.ID) AS TotalDeath

            FROM 
                _Guild
                    
            JOIN 
                _GuildMember ON _Guild.ID = _GuildMember.GuildID
            
            WHERE 
                _Guild.ID > 0
            
            GROUP BY 
                _Guild.ID, _Guild.Name
            
            ORDER BY 
                TotalKills DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    file_put_contents($file, json_encode($rows));
}

$data = json_decode(file_get_contents($file), true);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="silkroad, Silkroad Online, MMORPG, Free to play, GameGami, online oyun, oyun, silk, gold, SRO, Bot, f2p, hardcore mmorpg, Online game, free online mmorpg, Free game, joymax, pc game, free download, download" />
    <meta name="Description" content="Silkroad Online dünyanın en çok oynanan ücretsiz MMORPG oyunlarının başında gelmektedir. Silkroad Online'da eski Çin, İslam ve Avrupa medeniyetlerinin derinliklerine gidecek ve PvP, zindan sistemleri, sonsuz kale savaşları ile en iyi kahramanlardan biri olmak için çarpışacaksınız!" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Silkroad Online - Fortress War(Guild)</title>
    <link href="images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <!-- Coded by m1xawy -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<script type="text/javascript">
    function blankurl(val)
    {
        window.open(val, '_blank');
    }
</script>
<div id="rankmain" style="width:250px">
    <div id="rankmenu_container" style="width:245px; background:url('images/menubg1.png') 0 0 no-repeat">
        <ul>
            <li ><a href="fortressranking.php?fortId=2">Player</a></li>
            <li  class="selected"><a href="fortressranking_guild.php?fortId=2">Guild</a></li>
        </ul>
    </div>
    <script type="text/javascript">
        setTimeout('document.location.reload()', 15000);
    </script>
    <table class="table_rank" cellpadding="0" cellspacing="0" style="width:245px">
        <tr>
            <th class="th1" style="width:15px">#</th>
            <th class="th2" style="width:155px;text-align:left;padding-left:20px;">Guild</th>
            <th class="th3" style="width:50px">Kill</th>
        </tr>

        <?php if (!empty($data)): ?>
        <?php $i = 1 ?>
            <?php foreach ($data as $value): ?>
                <tr onMouseOver='this.style.background="#2e261e"' onMouseOut='this.style.background="none"'>
                    <td class="td1"><?= $i ?></td>
                    <td class="td4"><?= $value['Name'] ?></td>
                    <td class="td5"><?= $value['TotalKills'] ?></td>
                </tr>
                <?php $i++ ?>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align:center">No Records Found</td>
            </tr>
        <?php endif ?>
    </table>
</div>
</body>
</html>
