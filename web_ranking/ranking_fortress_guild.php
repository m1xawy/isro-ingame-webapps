<?php
require_once 'config.php';

$file = $_SERVER['DOCUMENT_ROOT'] . '/cache/ranking_fortress_guild.json';
$time = 3600;

if (!file_exists($file) || filemtime($file) < time() - $time) {
    $query = "SELECT TOP(20) 
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
    <title>Silkroad Online - Fortress War(Guild) Ranking</title>
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

<div id="rankmain">
    <div id="rankmenu_container">
        <ul>
            <li ><a href="ranking.php">Player</a></li>
            <li ><a href="ranking_guild.php">Guild</a></li>
            <li ><a href="ranking_unique.php">Unique</a></li>
            <li ><a href="ranking_level.php">Level</a></li>
            <li ><a href="ranking_fortress_player.php">Fortress War(Player)</a></li>
            <li class="selected"><a href="ranking_fortress_guild.php">Fortress War(Guild)</a></li>
        </ul>
    </div>
    <table class="table_rank" cellpadding="0" cellspacing="0">
        <tr>
            <th class="th1"> </th>
            <th class="th2">#</th>
            <th class="th4">Guild</th>
            <th class="th5">Kill</th>
            <th class="th6">Change</th>
        </tr>
        <?php if (!empty($data)): ?>
        <?php $i = 1 ?>
            <?php foreach ($data as $value): ?>
                <tr onMouseOver='this.style.background="#2e261e"' onMouseOut='this.style.background="none"'>
                    <td class="td1">
                        <?php
                        switch($i):
                            case 1:
                                echo '<img src="images/rank1.png" style="vertical-align:text-top" />';
                                break;
                            case 2:
                                echo '<img src="images/rank2.png" style="vertical-align:text-top" />';
                                break;
                            case 3:
                                echo '<img src="images/rank3.png" style="vertical-align:text-top" />';
                                break;
                        endswitch;
                        ?>
                    </td>
                    <td class="td2"><?= $i ?></td>
                    <td class="td4"><?= $value['Name'] ?></td>
                    <td class="td5"><?= $value['TotalKills'] == null ? 0 : $value['TotalKills'] ?></td>
                    <td class="td6"><center><img style="width: 16px; height: 16px;" src="images/nochange.png" title="No Change"></center></td>
                </tr>
            <?php $i++ ?>
            <?php endforeach; ?>
        <?php else: ?>
            <center>
                <tr><td data-toggle="collapse" colspan="5" class="react-bs-table-no-data" style="text-align: center">No data to display.</td></tr>
            </center>
        <?php endif ?>
    </table>

    <div id="button_website" onclick="blankurl('https://silkroad.gamegami.com')">Official Site</div>
</div>

</body>
</html>
