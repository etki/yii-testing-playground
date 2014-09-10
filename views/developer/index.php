<div class="col-md-12">
<h1>Our developers</h1>
    <div class="row">
<?php

/** @type Developer[] $developers */
if (!$developers) { ?>
    <div class="alert alert-warning">
        Sorry, no developers found
    </div>
<?php } else {
    foreach ($developers as $developer) {
        ?>
            <div class="col-md-4 developer">
                <div class="inner">
                    <div class="profile-photo">
                        <img src="http://gravatar.com/avatar/<?php echo md5(strtolower($developer['email'])); ?>?s=120">
                    </div>
                    <div class="name"><?php echo $developer['name']; ?></div>
                </div>
            </div>
        <?php
    }
} ?>
    </div>
</div>