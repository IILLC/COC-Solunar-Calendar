<!-- Template Name-->
<?php
/*
Template Name: Fishing Calendar
*/
?>
<!-- Template Name-->


<!-- START ----------- Page stuff at top-->

<?php
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-ui-autocomplete');
global $wpdb;
get_currentuserinfo(); 

$findFishDataSQL = "
Select
  coc_fishing_times_basic_table.day,
  coc_fishing_times_basic_table.sunrise,
  coc_fishing_times_basic_table.sunset,
  coc_fishing_times_basic_table.moonrise,
  coc_fishing_times_basic_table.moonset,
  coc_fishing_times_basic_table.moon_showing,
  coc_fishing_times_basic_table.major_start,
  coc_fishing_times_basic_table.overhead_score,
  coc_fishing_times_basic_table.rise_score,
  coc_fishing_times_basic_table.set_score,
  coc_fishing_times_basic_table.total_score,
  coc_fishing_times_basic_table.moon_pic
From
  coc_fishing_times_basic_table  
Where
  coc_fishing_times_basic_table.day >= CURDATE()
  And TO_DAYS( coc_fishing_times_basic_table.day ) - TO_DAYS( CURDATE() ) < 30  
Order By
  coc_fishing_times_basic_table.day";
$findFishDataResults = $wpdb->get_results( $findFishDataSQL );

?>


<!-- END ------------ Page stuff at top-->

<?php do_action( '__before_main_wrapper' ); ##hook of the header with get_header ?>
<div id="main-wrapper" class="<?php echo implode(' ', apply_filters( 'tc_main_wrapper_classes' , array('container') ) ) ?>">

    <?php do_action( '__before_main_container' ); ##hook of the featured page (priority 10) and breadcrumb (priority 20)...and whatever you need! ?>

    <div class="container" role="main">
        <div class="<?php echo implode(' ', apply_filters( 'tc_column_content_wrapper_classes' , array('row' ,'column-content-wrapper') ) ) ?>">

            <?php do_action( '__before_article_container'); ##hook of left sidebar?>

                <div id="content" class="<?php echo implode(' ', apply_filters( 'tc_article_container_class' , array( CZR_utils::czr_fn_get_layout( CZR_utils::czr_fn_id() , 'class' ) , 'article-container' ) ) ) ?>">

                    <?php do_action ('__before_loop');##hooks the heading of the list of post : archive, search... ?>

                        <?php if ( czr_fn__f('__is_no_results') || is_404() ) : ##no search results or 404 cases ?>

                            <article <?php czr_fn__f('__article_selectors') ?>>
                                <?php do_action( '__loop' ); ?>
                            </article>

                        <?php endif; ?>

                        <?php if ( have_posts() && ! is_404() ) : ?>
                            <?php while ( have_posts() ) : ##all other cases for single and lists: post, custom post type, page, archives, search, 404 ?>
                                <?php the_post(); ?>

                                <?php do_action ('__before_article') ?>
                                    <article <?php czr_fn__f('__article_selectors') ?>>
										<?php do_action( '__loop' ); ?>
                                    </article>
<!-- START --- Page stuff afer Post Text -->

        <div class="post-bodycopy clearfix">
		<table width="100%" border="0">
		  <tr>
		    <th>Day</th>
		    <th>Sun</th>
		    <th>Best Fishing</th>
		    <th>Good Fishing</th>
		    <th>Moon</th>
		    <th>&nbsp;</th>
	      </tr>
          
<?php 
$i = 1;
foreach ( $findFishDataResults as $fData ) { 
	$dayName = date( 'l', strtotime( $fData->day ) );
	
	$sRise = date( 'g:i a', strtotime( $fData->sunrise ) );
	$sSet = date( 'g:i a', strtotime( $fData->sunset ) );
	
	$mRise = date( 'g:i a', strtotime( $fData->moonrise ) );
	$mSet = date( 'g:i a', strtotime( $fData->moonset ) );	
	
	$fMajor1Start = date( 'g:i a', strtotime( $fData->major_start ) );
	$fTimeMod = strtotime( $fData->major_start ) + 60*60*2;
	$fMajor1End = date( 'g:i a', $fTimeMod );

	$fTimeMod = strtotime( $fData->major_start ) + 44760; // 12 hours 26 minutes
	$fMajor2Start = date( 'g:i a', $fTimeMod );
	$fTimeMod = strtotime( $fMajor2Start ) + 60*60*2; // 2 hours
	$fMajor2End = date('g:i a', $fTimeMod);
	
	$fTimeMod = strtotime( $fData->major_start ) + 24120; // 6 hours 42 minutes
	$fMinor1Start = date( 'g:i a', $fTimeMod );
	$fTimeMod = strtotime( $fMinor1Start ) + 60*60; // 1 hour
	$fMinor1End = date( 'g:i a', $fTimeMod );

	$fTimeMod = strtotime( $fData->major_start ) + 68760; // 19 hours 38 minutes
	$fMinor2Start = date( 'g:i a', $fTimeMod );
	$fTimeMod = strtotime( $fMinor2Start ) + 60*60; // 1 hour
	$fMinor2End = date( 'g:i a', $fTimeMod );
	
	if ( $fData->total_score >= 6 ) {
					$tMessage = 'The best fishing times are today. Get out there and enjoy it! ';
					$imageNum = 5;
				}
				elseif ( $fData->total_score <= 2 ) {
					$tMessage = 'There are no best fishing times today. It might be a better day for a hike. ';
					$imageNum = 0;
				}
				elseif ( ( $fData->total_score > 2 ) && ( $fData->total_score < 3 ) ) {
					$tMessage = 'Fishing will not be great. Anglers will have to work a little more today. ';
					$imageNum = 1;
				}
				elseif ( ( $fData->total_score >= 3 ) && ( $fData->total_score < 4 ) ) {
					$tMessage = 'Fishing will likely be OK. The action will be somewhat muted. ';
					$imageNum = 2;
				}
				elseif ( ( $fData->total_score >= 4 ) && ( $fData->total_score < 5 ) ) {
					$tMessage = 'Good fishing is likely during the major and minor feeding times. ';
					$imageNum = 3;
				}				
				elseif ( ( $fData->total_score >= 5 ) && ( $fData->total_score < 6 ) ) {
					$tMessage =  'Great fishing can be expected during both the major and minor feeding times. ';
					$imageNum = 4;
				}
				else {
					$tMessage = '';
				}
															
				if ( $fData->rise_score == 1 ) {
					$rMessage =  '<i>Great fishing is expected around sunrise. </i> ';
				}
				else {
					$rMessage =  '';
				}

				if ( $fData->set_score == 1 ) {
					$sMessage =  '<i>Great fishing is expected around sunset. </i> ';
				}
				else {
					$sMessage =  '';
				}

	$fishingMessages = $tMessage.$rMessage.$sMessage;

	if ( ( $i > 1 ) && ( $dayName == "Monday" ) ) { //adds a header row every week ?>
		  <tr>
		    <th>Day</th>
		    <th>Sun</th>
		    <th>Best Fishing</th>
		    <th>Good Fishing</th>
		    <th>Moon</th>
		    <th>&nbsp;</th>
	      </tr>    
    <?php } ?>

		  <tr>
		    <td><strong><?php echo $dayName ?></strong></td>
		    <td>Rise <?php echo $sRise ?></td>
		    <td><?php echo $fMajor1Start ?>-<?php echo $fMajor1End ?></td>
		    <td><?php echo $fMinor1Start ?>-<?php echo $fMinor1End ?></td>
		    <td>Rise <?php echo $mRise ?></td>
		    <td rowspan="2"><img src="~/../../wp-content/themes/ata-child-coc/campoutcolorado-best-fishing-time-moon-phase-<?php echo $fData->moon_pic ?>.jpg" width="50" height="50" /></td>
	      </tr>
		  <tr>
		    <td><?php echo $fData->day ?></td>
		    <td>Set <?php echo $sSet ?></td>
		    <td><?php echo $fMajor2Start ?>-<?php echo $fMajor2End ?></td>
		    <td><?php echo $fMinor2Start ?>-<?php echo $fMinor2End ?></td>
		    <td>Set <?php echo $mSet ?></td>
	      </tr>
		  <tr style="border-bottom:thick solid; border-color:#666;">
		    <td><img src="~/../../wp-content/themes/ata-child-coc/campoutcolorado-best-fishing-time-fish-scale-<?php echo $imageNum ?>.jpg" alt="Camp Out Colorado Best Fishing Time Fish Scale" width="100" height="25" title="Camp Out Colorado Best Fishing Time Rating"/></td>
		    <td colspan="5"><?php echo $fishingMessages; ?></td>
	      </tr>
<?php 
	$i++;
} //end table loop 
?>          
      </table>
		<p>&nbsp;</p>
		<h3> Camp Out Colorado's Best Fishing Times Solunar Calendar Notes:</h3>
		<p>Each day is rated 0 to 5 fish. Just like 5 stars, 5 fish is a great day to go fishing or hunting. On the days of worse fishing you will find a hiking icon instead of fish. It may just be better on those days to take some time off from hunting and fishing and enjoy the many other things the great outdoors has to offer!</p>
		<p>All of these best fishing times are given in Mountain Standard Time (GMT -7). If you are outside of that time zone, just add or subract a little time to get the best fishing and hunting times for your local area.</p>
		<p>Bad weather will usually have an effect on fishing and hunting. If the barometer is going down it will usually lower your chances for good fishing. On the other hand, if the barometer is steady or rising, fishing will likely be better.</p>
		<p><strong>Best Fishing</strong> is the best time during the day to go fishing. This is when there will likely be the most activity with fish and game. <strong>Good Fishing</strong> is the minor periods of activity and will generally be a little slower but still better than the rest of the day.</p>
		<p>There are some short notes for each day to further explain the best fishing times. Sundown and sunset can both be good times for fishing or hunting.  Camp Out Colorado's Best Fishing Times Solunar Calendar will make note of the best times during sunrise and sunset as well.</p>
		<p>Make sure to bookmark, Like, and share Camp Out Colorado's Best Fishing Times Solunar Calendar with your fellow outdoor enthusiasts. Camp Out Colorado wishes you the best of luck fishing and hunting. And remember, if the fish aren't biting it's because you didn't share this solunar calendar with them!</p>
        
        </div>

<!-- END --- Page stuff afer Post Text -->


                                <?php do_action ('__after_article') ?>

                            <?php endwhile; ?>

                        <?php endif; ##end if have posts ?>

                    <?php do_action ('__after_loop');##hook of the comments and the posts navigation with priorities 10 and 20 ?>

                </div><!--.article-container -->

           <?php do_action( '__after_article_container'); ##hook of left sidebar ?>

        </div><!--.row -->
    </div><!-- .container role: main -->

    <?php do_action( '__after_main_container' ); ?>

</div><!--#main-wrapper"-->

<?php do_action( '__after_main_wrapper' );##hook of the footer with get_footer ?>
