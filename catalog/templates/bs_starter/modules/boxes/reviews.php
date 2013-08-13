<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/reviews.php start-->
<div class="well" >
  <ul class="box-reviews nav nav-list">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $(".box-reviews li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('margin-left-li');
  });
  var imageContent = $('.box-reviews-image').html();
  $('.box-reviews-image').html('<div class="thumbnail">' + imageContent + '</div>');  
  $(".box-reviews li:last-child").addClass('small-margin-top align-center');
});
</script>
<!--modules/boxes/reviews.php end-->