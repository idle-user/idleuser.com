<?php require_once getenv('APP_PATH') . '/src/session.php'; set_last_page(); requires_admin(); ?>
<?php
    $open_matches = array_slice($db->open_matches(), 0, 5);
    $recent_match_updates = array_slice($db->all_matches_recently_updated(), 0, 5);
    $recent_polls =  array_slice($db->all_polls(), 0, 5);
    $recent_users = $db->all_recent_users();

?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Admin - Home';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="container">

        <?php include 'includes/banner.php'; ?>

        <!-- Last 5 open matches -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Open Matches<small class="float-right">limit 5</small></h6>
            <?php if(empty($open_matches)){ ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <small class="d-block float-right"></small>
                        <strong class="d-block text-gray-dark">No Open Matches found.</strong>
                    </p>
                </div>
            <?php } else {
                foreach($open_matches as $om){
            ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <a class="d-block float-right" href="matches-editor?search=<?php echo $om['id'] ?>"><?php echo $om['info_last_updated'] ?></a>
                        <strong class="d-block text-gray-dark"><?php echo $om['event'].' - '.$om['title'].' - '.$om['match_type'] ?></strong>
                        <?php echo $om['contestants'] ?><small class="float-right"><?php echo $om['info_last_updated_by_username'] ?></small>
                    </p>
                </div>
            <?php
                }
            }
             ?>
            <small class="d-block text-right mt-3">
                <a href="matches-editor">All Open Matches</a>
            </small>
        </div>

        <!-- Last 5 matches updates -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Matches Recently Updated<small class="float-right">limit 5</small></h6>
            <?php if(empty($recent_match_updates)){ ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <small class="d-block float-right"></small>
                        <strong class="d-block text-gray-dark">No Matches found.</strong>
                    </p>
                </div>
            <?php } else {
                foreach($recent_match_updates as $rm){
            ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <a class="d-block float-right" href="matches-editor?search=<?php echo $rm['id'] ?>"><?php echo $rm['info_last_updated'] ?></a>
                        <strong class="d-block text-gray-dark"><?php echo $rm['event'].' - '.$rm['title'].' - '.$rm['match_type'] ?></strong>
                        <?php echo $rm['contestants'] ?><small class="float-right"><?php echo $rm['info_last_updated_by_username'] ?></small>
                    </p>
                </div>
            <?php
                }
            }
             ?>
        </div>

        <!-- Last 5 poll topics created -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Create-a-Poll Recently Created<small class="float-right">limit 5</small></h6>
            <?php if(empty($recent_polls)){ ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <small class="d-block float-right"></small>
                        <strong class="d-block text-gray-dark">No Poll Topics found.</strong>
                    </p>
                </div>
            <?php } else {
                foreach($recent_polls as $rp){
            ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <a class="d-block float-right" href="/projects/create-a-poll/vote?id=<?php echo $rp['id'] ?>"><?php echo $rp['created_dt'] ?></a>
                        <strong class="d-block text-gray-dark"><?php echo $rp['content'] ?></strong>
                        <?php echo $rp['ending_in']<0?'expired':'' ?><small class="float-right"><?php echo $rp['username'] ?></small>
                    </p>
                </div>
            <?php
                }
            }
             ?>
            <small class="d-block text-right mt-3">
                <a href="#">All Polls</a>
            </small>
        </div>

        <!-- Latest user registers -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Newest Members<small class="float-right">limit 5</small></h6>
            <?php if(empty($recent_users)){ ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <small class="d-block float-right"></small>
                        <strong class="d-block text-gray-dark">No recent members found.</strong>
                    </p>
                </div>
            <?php } else {
                foreach(array_slice($recent_users, 0, 5) as $ru){
            ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <text class="d-block float-right"><?php echo $ru['date_created'] ?></text>
                        <strong class="d-block text-gray-dark"><?php echo $ru['username'] ?></strong>
                        <small><?php echo $ru['discord_id']?'+Discord':' '; echo $ru['chatango_id']?'+Chatango':' ' ?></small>
                    </p>
                </div>
            <?php
                }
            }
             ?>
        </div>

    </main>

	<?php include getenv('APP_PATH') . '/public/includes/footer.php'; ?>
</body>



</html>
