<?php
if ($response) {

    switch ($response['statusCode']) {
        case '200':
            $alertStyle = 'alert-success';
            $alertHeading = $successMessage ?? 'Successfully updated.';
            $alertMessage = '';
            break;
        case '422':
            $alertStyle = 'alert-warning';
            $alertHeading = $response['error']['type'];
            $alertMessage = $response['error']['description'];
            break;
        default:
            $alertStyle = 'alert-danger';
            $alertHeading = $response['error']['type'];
            $alertMessage = $response['error']['description'];
    }
    ?>
    <div class="alert text-center pb-4 <?php echo $alertStyle; ?>" role="alert">
        <h5 class="alert-heading"><?php echo $alertHeading; ?></h5>
        <p><?php echo $alertMessage; ?></p>
        <hr>
        <p class="small float-right"><?php echo $response['statusCode']; ?></p>
    </div>
    <?php
}
?>
