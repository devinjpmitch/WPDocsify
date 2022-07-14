<section class="wpdocsify-error">
    <div><img src="<?php echo plugins_url('/img/notfound.svg', __FILE__); ?>" title="WPDocsify Error <?= $error['title']; ?>" />
        <div>
            <h1><?= $error['title']; ?></h1>
            <h2><?= $error['error']; ?></h2>
            <code><?=$dir_absolute; ?></code>
        </div>
    </div>
</section>
<style>
    html {
        overflow: hidden
    }

    .php-error #adminmenuwrap {
        margin: 0
    }

    .wpdocsify-error {
        height: calc(100vh - 50px);
        padding: 0;
        margin: 0;
        display: grid;
        align-items: center;
        justify-content: center;
        text-align: center
    }

    .wpdocsify-error h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        color: #9ca2a7
    }

    .wpdocsify-error img {
        width: 250px;
        margin: auto
    }
</style>