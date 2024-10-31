<div id="rii-player" class="rii-<?php echo esc_attr( $skin ); ?>-skin clearfix" data-interval="<?php echo esc_attr( $interval ); ?>" data-channel="<?php echo esc_attr( $channel ); ?>">
    
    <div id="rii-current-played" class="clearfix" data-info>
        <p class="intro"><?php _e( 'Radio Islam Indonesia', 'rii' ); ?></p>
    </div>

    <!-- PLAYER BEGIN -->
    <div id="rii-player-interface" class="jp-jplayer"></div>
    <!-- PLAYER END -->

    <!-- PLAYLIST BEGIN -->
    <div id="rii-playlist" class="clearfix">
        <div id="rii-playlist-content"></div>
    </div>
    <!-- PLAYLIST END -->

    <!-- CONTROLS BEGIN -->
    <div class="rii-source-filter">
        <input data-search type="text" placeholder="<?php esc_attr_e( 'Cari radio', 'rii' ); ?>">
    </div>

    <div id="rii-controls" class="clearfix">
        <div id="rii-prev" class="rii-control"><i class="icon-control-rewind icons"></i></div>
        <div id="rii-play-pause">
            <div id="rii-play" class="rii-control"><i class="icon-control-play icons"></i></div>
            <div id="rii-pause" class="rii-control"><i class="icon-control-pause icons"></i></div>
        </div>
        <div id="rii-next" class="rii-control"><i class="icon-control-forward icons"></i></div>
        <div id="rii-toggle-playlist" class="rii-control">
            <i class="icon-list icons"></i>
        </div>
        <div id="rii-search-channel" class="rii-control">
            <i class="icon-magnifier icons"></i>
        </div>
        
        <!--<div id="rii-timeremain" class="clearfix">
            <!--<div id="rii-currentTime"></div>
            <!--<div id="duration"></div>
        </div>-->
    </div>
    <!-- CONTROLS END -->

</div>
<?php if ( $credits ) { ?>
<div class="rii-copyright"><span><?php printf( __( 'Designed by <a href="%s" target="_blank">Dakwah Studio</a> for OaseMedia', 'rii' ), esc_url( 'http://dakwahstudio.com' ) ) ?></span></div>
<?php } ?>