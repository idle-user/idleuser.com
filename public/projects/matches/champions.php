<?php include 'header.php';
?>
<header class="main">
    <h1>Champions</h1>
</header>
<div class="table-wrapper">
    <table class="alt">
        <thead>
        <tr>
            <th>Championship</th>
            <th>History</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($db->all_titles() as $title) { ?>
            <tr>
                <td><?php echo $title['name']; ?></td>
                <td><a href="/projects/matches/matches?title_id=<?php echo $title['id']; ?>">Matches</a></td>
            </tr>
        <?php } ?>
    </table>
</div>
<?php include 'navi-footer.php'; ?>
