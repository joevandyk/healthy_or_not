<?php get_header(); ?>

  <div id="content" class="narrowcolumn">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div class="navigation clear">
      <div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
      <div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
    </div>

    <div class="post" id="post-<?php the_ID(); ?>">
      <div class="post-content">
        <?php if (has_tag("healthy")) : ?><h3 class="healthy">Healthy</h3><?php endif; ?>
        <?php if (has_tag("not")) : ?><h3 class="not">Not</h3><?php endif; ?>
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
        <div class="postmetadata">
          <!-- by <?php the_author() ?> -->
          <small><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;', 'comments-link'); ?></small>
          <div class="date"><a title="Permanent Link to <?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><span class="day"><?php the_time('D') ?></span> <?php the_time('M j') ?></a></div>
        </div>

        <div class="entry">
          <?php the_content('<p class="serif">Continue Reading &raquo;</p>'); ?>

          <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
          <?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

          <!--<p class="postmetadata alt">
            <small>
              This entry was posted
              <?php /* This is commented, because it requires a little adjusting sometimes.
                You'll need to download this plugin, and follow the instructions:
                http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
                /* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
              on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
              and is filed under <?php the_category(', ') ?>.
              You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.

              <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
                // Both Comments and Pings are open ?>
                You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

              <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
                // Only Pings are Open ?>
                Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

              <?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
                // Comments are open, Pings are not ?>
                You can skip to the end and leave a response. Pinging is currently not allowed.

              <?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
                // Neither Comments, nor Pings are open ?>
                Both comments and pings are currently closed.

              <?php } edit_post_link('Edit this entry','','.'); ?>

            </small>
          </p>-->
        </div>
      </div>
    </div>

  <?php comments_template(); ?>

  <?php endwhile; else: ?>

    <p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

  </div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
