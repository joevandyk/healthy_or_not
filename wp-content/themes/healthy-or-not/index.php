<?php get_header(); ?>

  <div id="content" class="narrowcolumn">

  <?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

      <div class="post" id="post-<?php the_ID(); ?>">
        <div class="post-content">
          <?php if (has_tag("healthy")) : ?><h3 class="healthy">Healthy</h3><?php endif; ?>
          <?php if (has_tag("not")) : ?><h3 class="not">Not</h3><?php endif; ?>
        
          <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
          <div class="postmetadata">
            <!-- by <?php the_author() ?> -->
            <small><!--<?php the_tags('Tags: ', ', ', '<br />'); ?>--> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;', 'comments-link'); ?></small>
            <div class="date"><a title="Permanent Link to <?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><span class="day"><?php the_time('D') ?></span> <?php the_time('M j') ?></a></div>
          </div>
        
          <div class="entry">
            <?php the_content('Continue Reading &raquo;'); ?>
          </div>
        </div>
      </div>

    <?php endwhile; ?>

    <div class="navigation">
      <div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
      <div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
    </div>

  <?php else : ?>

    <h2 class="center">Not Found</h2>
    <p class="center">Sorry, but you are looking for something that isn't here.</p>
    <?php include (TEMPLATEPATH . "/searchform.php"); ?>

  <?php endif; ?>

  </div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
